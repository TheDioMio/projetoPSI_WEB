<?php

namespace frontend\controllers;

use common\models\Comment;
use frontend\models\ListingSearch;
use yii;
use common\models\Animal;
use common\models\File;
use common\models\Listing;
use common\models\AnimalType;
use common\models\Breed;

use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;
use yii\helpers\ArrayHelper;


class ListingsController extends Controller
{

    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'access' => [
                    'class' => AccessControl::class,
                    'denyCallback' => function () {
                        if (Yii::$app->user->can('loginFrontend')) {
                            return Yii::$app->response->redirect(['/site/index']);
                        }
                        return Yii::$app->response->redirect(['/site/login']);
                    },
                    'except' => ['error', 'animal', 'detail'],
                    'rules' => [
                        [
                            'actions' => ['create-listing', 'upload', 'delete', 'user-listings', 'update'],
                            'allow' => true,
                            'roles' => ['loginFrontend', 'listingsManeger'],
                        ],
                        [
                            'actions' => ['statistics'],
                            'allow' => true,
                            'roles' => ['statisticsPage'],
                        ],
                        [
                            'actions' => ['favourites', 'my-listings'],
                            'allow' => true,
                            'roles' => ['@'],
                        ],
                    ],
                ],

                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    public function actionAnimal()
    {

        $searchModel = new ListingSearch();

        if(array_key_exists('ListingSearch',$this->request->queryParams)){

        }
        $dataProvider = $searchModel->search($this->request->queryParams);
        $dataProvider->pagination->pageSize = 10;
        $dataProvider->sort->defaultOrder = [
            'created_at' => SORT_DESC,
        ];
        return $this->render('animal', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,

        ]);
    }

    public function actionDetail($id)
    {
        $model = Animal::findOne($id);
        if ($model === null) {
            throw new NotFoundHttpException('O animal que procura não existe.');
        }

        if ($model->listing) {
            $model->listing->updateCounters(['views' => 1]);
        }
        // 3. Vamos buscar os Comments do animal e enviamos para a vista

        // Comentários via relação: Animal → Listing → Comments
        $comments = $model->listing
            ? $model->listing
                ->getComments()
                ->with(['user', 'userImage'])
                ->orderBy(['created_at' => SORT_DESC])
                ->all()
            : [];
        $newComment = new Comment();

        // 4. O Controller ENVIA o $model para a View
        return $this->render('detail', [
            'model' => $model,
            'comments' => $comments,
            'newComment' => $newComment,
        ]);
    }

    public function actionCreateListing()
    {
        // falta criar a permissão e validar
        // if (!Yii::$app->user->can('createListing')) {
        //     throw new ForbiddenHttpException();
        // }

        $model = new Animal();
        $listingModel = new Listing();
        $address = Yii::$app->user->identity->address;

        $model->scenario = 'create';
        // tipos de animal e raças
        $animalTypes = ArrayHelper::map(
            AnimalType::find()->orderBy('description')->all(),
            'id',
            'description'
        );

        $breedsByType = [];
        $breeds = Breed::find()->orderBy('description')->all();

        foreach ($breeds as $breed) {
            $breedsByType[$breed->animal_type_id][$breed->id] = $breed->description;
        }

        if ($this->request->isPost) {

            // 2. Carregar os dados do formulário (name, age, etc.)
            $model->load(Yii::$app->request->post());
            $listingModel->load(Yii::$app->request->post());
            $model->user_id = Yii::$app->user->id;


            // 3. Apanhar as instâncias dos ficheiros
            $model->imageFiles = UploadedFile::getInstances($model, 'imageFiles');

            // 4. Validar o modelo (incluindo as regras dos 'imageFiles')
            if ($model->validate()) {

                // 5. Iniciar uma Transação
                $transaction = Yii::$app->db->beginTransaction();
                try {

                    // 6. Definir o dono e guardar o ANIMAL

                    if (!$model->save(false)) { // 'false' para não validar outra vez
                        throw new \Exception('Falha ao guardar o animal.');
                    }


                    // 7. Guardar os Ficheiros
                    $animalId = $model->id;

                    // Criar pasta: uploads/animals/{id}
                    $directory = Yii::getAlias('@webroot/uploads/animals/' . $animalId);
                    if (!is_dir($directory)) {
                        mkdir($directory, 0777, true);
                    }

                    // Loop pelas imagens
                    foreach ($model->imageFiles as $file) {

                        $filename = uniqid() . '.' . $file->extension;
                        $relative = 'uploads/animals/' . $animalId . '/' . $filename;
                        $absolute = Yii::getAlias('@webroot') . '/' . $relative;

                        // Guardar ficheiro físico
                        if (!$file->saveAs($absolute)) {
                            throw new \Exception('Falha ao guardar o ficheiro no disco.');
                        }

                        // Guardar na BD (tabela file)
                        $image = new File();
                        $image->animal_id = $animalId;
                        $image->user_id = $model->user_id;
                        $image->type_id = 1; // tipo animal
                        $image->path = '/' . $relative;
                        $image->created_at = date('Y-m-d H:i:s');

                        if (!$image->save(false)) {
                            throw new \Exception('Falha ao guardar o caminho na BD.');
                        }
                    }

                    // 8. (Opcional) Criar o Listing (Anúncio)
                    $listingModel->animal_id = $model->id;
                    $listingModel->user_id = Yii::$app->user->id;


                    if (!$listingModel->save()) {
                        throw new \Exception('Falha ao criar o anúncio (listing).');
                    }

                    $model->status = $listingModel->status;
                    $model->statusDate = date('Y-m-d');
                    if (!$model->save(false)) {
                        throw new \Exception('Falha ao sincronizar estado do animal.');
                    }

                    // 9. Se tudo correu bem, 'cometer' a transação
                    $transaction->commit();
                    Yii::$app->session->setFlash('success', 'Anúncio criado com sucesso!');
                    return $this->redirect(['detail', 'id' => $model->id]);

                } catch (\Exception $e) {
                    // 10. Se algo falhou, fazer 'rollback' (desfazer tudo)
                    $transaction->rollBack();
                    Yii::$app->session->setFlash('error', 'Ocorreu um erro: ' . $e->getMessage());
                }
            } else {
                // Erro de validação (ex: nome em branco ou foto em falta)
                 Yii::$app->session->setFlash('error', 'Por favor, corrija os erros no formulário.');
            }
        }

        // 11. (QUANDO A PÁGINA É CARREGADA PELA 1ª VEZ)
        // Enviar o $model (vazio) para a view
        return $this->render('create-listing', [
            'model' => $model,
            'listingModel' => $listingModel,
            'userAddress' => $address,
            'statusOptions' => $this->getStatusOptionsForCurrentUser(),
            'animalTypes' => $animalTypes,
            'breedsByType' => $breedsByType,
            ]);
    }

    public function actionUpload()
    {
        $model = new File();

        if (Yii::$app->request->isPost) {
            $model->imageFile = UploadedFile::getInstances($model, 'imageFile');
            if ($model->upload()) {
                // file is uploaded successfully
                return $this->goHome();
            }
        }

        return $this->render('upload', ['model' => $model]);
    }

    public function actionMyListings()
    {
        $userId = Yii::$app->user->id;

        $searchModel = new ListingSearch();
        $searchModel->search($this->request->queryParams);

        $query = Listing::find()
            ->leftJoin('animal', '`listing`.`animal_id` = `animal`.`id`')
            ->with(['animal.files'])
            ->where(['!=', 'listing.status', Listing::STATUS_DELETED])
            ->andWhere(['listing.user_id' => $userId])
            ->orderBy(['listing.created_at' => SORT_DESC]);

        if(array_key_exists('ListingSearch',$this->request->queryParams)) {
            $searchParameters = $this->request->queryParams['ListingSearch'];

            if ($searchParameters['animal_type_id']) {
                $query->andWhere(['animal.animal_type_id' => $searchParameters['animal_type_id']]);
            }

            if ($searchParameters['breed_id']) {
                $query->andWhere(['animal.breed_id' => $searchParameters['breed_id']]);
            }

            if ($searchParameters['animal_age_id']) {
                $query->andWhere(['animal.age_id' => $searchParameters['animal_age_id']]);
            }

            if ($searchParameters['animal_size_id']) {
                $query->andWhere(['animal.size_id' => $searchParameters['animal_size_id']]);
            }


        }

        $provider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        return $this->render('my-listings', [
            'provider' => $provider,
            'listings' => $provider->getModels(),
            'userId'   => $userId,
            'searchModel' => $searchModel,
        ]);
    }

    public function actionUserListings($id)
    {
        $query = Listing::find()
            ->where(['user_id' => $id, 'status' => 1]) // apenas ativos
            ->orderBy(['created_at' => SORT_DESC]);

        $provider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        return $this->render('user-listings', [
            'provider' => $provider,
            'listings' => $provider->getModels(),
            'userId'   => $id,
        ]);
    }

    public function actionDelete($id)
    {
        $listing = Listing::findOne($id);

        if (!$listing) {
            throw new NotFoundHttpException('Anúncio não encontrado.');
        }

        // Verificar se pertence ao user
        if ($listing->user_id != Yii::$app->user->id) {
            throw new ForbiddenHttpException('Não tem permissão para apagar este anúncio.');
        }

        // Atualizar estados
        $animal = $listing->animal;

        if ($animal) {
            $animal->status = 2; // Deleted
            $animal->save(false);
        }

        $listing->status = 2; // Deleted
        $listing->save(false);

        Yii::$app->session->setFlash('success', 'Anúncio apagado com sucesso.');

        return $this->redirect(['listing/my-listings']);
    }

    public function actionUpdate($id)
    {
        $listingModel = Listing::findOne($id);

        if (!$listingModel) {
            throw new NotFoundHttpException('Anúncio não encontrado.');
        }

        // só o dono pode editar
        if ($listingModel->user_id != Yii::$app->user->id) {
            throw new ForbiddenHttpException('Não tem permissão para editar este anúncio.');
        }

        if (!Yii::$app->user->can('updateAnimalStatus')) {
            throw new ForbiddenHttpException('Não tem permissão para editar o estado do anúncio.');
        }

        $model = $listingModel->animal;
        $model->scenario = 'update';

        //tipos de animal e raças

        $animalTypes = ArrayHelper::map(
            AnimalType::find()->orderBy('description')->all(),
            'id',
            'description'
        );

        $breedsByType = [];
        $breeds = Breed::find()->orderBy('description')->all();

        foreach ($breeds as $breed) {
            $breedsByType[$breed->animal_type_id][$breed->id] = $breed->description;
        }

        // Imagens atuais
        $existingImages = File::find()
            ->where(['animal_id' => $model->id])
            ->all();

        if (Yii::$app->request->isPost) {

            $model->load(Yii::$app->request->post());
            $listingModel->load(Yii::$app->request->post());


            $allowedStatuses = array_keys($this->getStatusOptionsForCurrentUser());
            if (!in_array($listingModel->status, $allowedStatuses)) {
                throw new \yii\web\ForbiddenHttpException('Estado inválido.');
            }

            $model->status = $listingModel->status;
            $model->statusDate = date('Y-m-d');

            // Apanhar novas imagens, se houver
            $model->imageFiles = UploadedFile::getInstances($model, 'imageFiles');

            // Contar quantas imagens existem antes guardar
            $existingCount = File::find()
                ->where(['animal_id' => $model->id])
                ->count();

            $newCount = count($model->imageFiles);

            //Se não houver imagens existentes E não houve upload novo
            if ($existingCount == 0 && $newCount == 0) {
                Yii::$app->session->setFlash('error', 'O anúncio deve ter pelo menos 1 fotografia.');
                return $this->redirect(['update', 'id' => $id]);
            }

            // Guardar modelos
            if ($model->save() && $listingModel->save()) {

                // Se houver novas imagens → guardamos
                if ($newCount > 0) {

                    $userId = $model->user_id;
                    $animalId = $model->id;

                    $basePath = Yii::getAlias('@storagePath') . "/users/{$userId}/animals/{$animalId}";
                    $baseUrl = Yii::getAlias('@storageUrl') . "/users/{$userId}/animals/{$animalId}";

                    \yii\helpers\FileHelper::createDirectory($basePath);

                    foreach ($model->imageFiles as $file) {

                        $fileName = Yii::$app->security->generateRandomString() . '.' . $file->extension;
                        $path = $basePath . '/' . $fileName;

                        $file->saveAs($path);

                        $fileModel = new File();
                        $fileModel->user_id = $userId;
                        $fileModel->animal_id = $animalId;
                        $fileModel->type_id = 1;
                        $fileModel->path = $baseUrl . '/' . $fileName;
                        $fileModel->save(false);
                    }
                }

                Yii::$app->session->setFlash('success', 'Anúncio atualizado com sucesso!');
                return $this->redirect(['detail', 'id' => $model->id]);
            }
        }

        return $this->render('create-listing', [
            'model' => $model,
            'listingModel' => $listingModel,
            'existingImages' => $existingImages,
            'statusOptions' => $this->getStatusOptionsForCurrentUser(),
            'animalTypes' => $animalTypes,
            'breedsByType' => $breedsByType,
        ]);
    }

    public function actionFavourites()
    {
        return $this->render('favourites');
    }

    public function actionStatistics(){
        // 1. Obtém o ID do user logado
        $userId = Yii::$app->user->id;

        // 2. Instancia o Search Model
        $searchModel = new ListingSearch();

        // 3. Obtém todos os dados processados
        $stats = $searchModel->getUserStatistics($userId);

        // 4. Envia para a view
        return $this->render('statistics', [
            'stats' => $stats,
        ]);
    }


    private function getStatusOptionsForCurrentUser()
    {
        $auth = Yii::$app->authManager;
        $roles = $auth->getRolesByUser(Yii::$app->user->id);

        return isset($roles['UserPro'])
            ? Listing::getAllowedStatusesForUserPro()
            : Listing::getAllowedStatusesForUser();
    }


}