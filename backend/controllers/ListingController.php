<?php

namespace backend\controllers;

use common\models\Animal;
use common\models\AnimalAge;
use common\models\AnimalSize;
use common\models\AnimalType;
use common\models\Breed;
use common\models\File;
use common\models\Listing;
use backend\models\ListingSearch;
use common\models\User;
use common\models\Vaccination;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\web\UploadedFile;

class ListingController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'access' => [
                    'class' => AccessControl::class,
                    'denyCallback' => function () {
                        if (Yii::$app->user->can('loginBackend')) {
                            return Yii::$app->response->redirect(['/site/index']);
                        }
                        return Yii::$app->response->redirect(['/site/login']);
                    },
                    'except' => ['error'],
                    'rules' => [
                        [
                            'allow' => true,
                            'roles' => ['loginBackend'],
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

    public function beforeAction($action)
    {
        if (!parent::beforeAction($action)) {
            return false;
        }
        $this->view->params['userLogado'] = Yii::$app->user->identity;
        return true;
    }

    public function actionIndex()
    {
        $searchModel = new ListingSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionCreate()
    {
        //Instanciar os 2 modelos
        $listingModel = new Listing();
        $animalModel = new Animal();

        // Dropdowns
        $animalTypes = ArrayHelper::map(AnimalType::find()->orderBy('description')->all(), 'id', 'description');
        $idades = ArrayHelper::map(AnimalAge::find()->all(), 'id', 'description');
        $portes = ArrayHelper::map(AnimalSize::find()->all(), 'id', 'description');
        $vacinas = ArrayHelper::map(Vaccination::find()->all(), 'id', 'description');
        $users = ArrayHelper::map(User::find()->all(), 'id', 'username');

        $breedsByType = [];
        $breeds = Breed::find()->orderBy('description')->all();
        foreach ($breeds as $breed) {
            $breedsByType[$breed->animal_type_id][$breed->id] = $breed->description;
        }

        if ($this->request->isPost) {

            $listingModel->load($this->request->post());
            $animalModel->load($this->request->post());

            $animalModel->imageFiles = UploadedFile::getInstances($animalModel, 'imageFiles');

            if (empty($animalModel->user_id)) {
                $animalModel->user_id = Yii::$app->user->id;
            }
            $listingModel->user_id = $animalModel->user_id;

            // Validar o Animal completo
            $validAnimal = $animalModel->validate();
            // Validar o anúncio nos campos que o user preencheu
            $validListing = $listingModel->validate(['description', 'status', 'user_id']);

            if ($validAnimal && $validListing) {

                $transaction = Yii::$app->db->beginTransaction();
                try {
                    //A. Guardar Animal
                    $animalModel->status = $listingModel->status;
                    $animalModel->statusDate = date('Y-m-d');

                    if (!$animalModel->save(false)) {
                        throw new \Exception('Erro ao guardar animal.');
                    }

                    //B. Guardar Anúncio
                    $listingModel->animal_id = $animalModel->id;

                    if (!$listingModel->save(false)) {
                        throw new \Exception('Erro ao guardar anúncio.');
                    }

                    //C. Guardar Imagens
                    $basePath = Yii::getAlias('@frontend/web/uploads/animals/' . $animalModel->id);
                    if (!is_dir($basePath)) {
                        mkdir($basePath, 0777, true);
                    }

                    foreach ($animalModel->imageFiles as $file) {
                        $filename = uniqid() . '.' . $file->extension;
                        $path = $basePath . '/' . $filename;

                        if ($file->saveAs($path)) {
                            $fileDb = new File();
                            $fileDb->animal_id = $animalModel->id;
                            $fileDb->user_id = $animalModel->user_id;
                            $fileDb->type_id = 1;
                            $fileDb->path = '/uploads/animals/' . $animalModel->id . '/' . $filename;
                            $fileDb->created_at = date('Y-m-d H:i:s');
                            $fileDb->save(false);
                        }
                    }

                    $transaction->commit();
                    Yii::$app->session->setFlash('success', 'Anúncio e Animal criados com sucesso!');
                    return $this->redirect(['view', 'id' => $listingModel->id]);

                } catch (\Exception $e) {
                    $transaction->rollBack();
                    Yii::$app->session->setFlash('error', $e->getMessage());
                }
            }
        }

        return $this->render('create', [
            'listingModel' => $listingModel,
            'animalModel' => $animalModel,
            'animalTypes' => $animalTypes,
            'breedsByType' => $breedsByType,
            'idades' => $idades,
            'portes' => $portes,
            'vacinas' => $vacinas,
            'users' => $users,
        ]);
    }

    public function actionUpdate($id) {
        $listingModel = $this->findModel($id);
        $animalModel = $listingModel->animal;
        $animalModel->scenario = 'update';

        // Recarregar Dropdowns
        $animalTypes = ArrayHelper::map(AnimalType::find()->orderBy('description')->all(), 'id', 'description');
        $idades = ArrayHelper::map(AnimalAge::find()->all(), 'id', 'description');
        $portes = ArrayHelper::map(AnimalSize::find()->all(), 'id', 'description');
        $vacinas = ArrayHelper::map(Vaccination::find()->all(), 'id', 'description');
        $users = ArrayHelper::map(User::find()->all(), 'id', 'username');

        $breedsByType = [];
        $breeds = Breed::find()->orderBy('description')->all();
        foreach ($breeds as $breed) {
            $breedsByType[$breed->animal_type_id][$breed->id] = $breed->description;
        }

        // Imagens existentes
        $existingImages = File::find()->where(['animal_id' => $animalModel->id])->all();

        if ($this->request->isPost) {
            $listingModel->load($this->request->post());
            $animalModel->load($this->request->post());

            // Sincronizar ID do dono e Status
            $listingModel->user_id = $animalModel->user_id;
            $animalModel->status = $listingModel->status;

            $animalModel->imageFiles = UploadedFile::getInstances($animalModel, 'imageFiles');

            if ($animalModel->validate() && $listingModel->validate()) {

                if ($animalModel->save() && $listingModel->save()) {

                    // Upload de NOVAS imagens (se existirem)
                    if (count($animalModel->imageFiles) > 0) {
                        $basePath = Yii::getAlias('@frontend/web/uploads/animals/' . $animalModel->id);
                        if (!is_dir($basePath)) { mkdir($basePath, 0777, true); }

                        foreach ($animalModel->imageFiles as $file) {
                            $filename = uniqid() . '.' . $file->extension;
                            $path = $basePath . '/' . $filename;
                            if ($file->saveAs($path)) {
                                $fileDb = new File();
                                $fileDb->animal_id = $animalModel->id;
                                $fileDb->user_id = $animalModel->user_id;
                                $fileDb->type_id = 1;
                                $fileDb->path = '/uploads/animals/' . $animalModel->id . '/' . $filename;
                                $fileDb->created_at = date('Y-m-d H:i:s');
                                $fileDb->save(false);
                            }
                        }
                    }

                    Yii::$app->session->setFlash('success', 'Atualizado com sucesso.');
                    return $this->redirect(['view', 'id' => $listingModel->id]);
                }
            }
        }

        return $this->render('update', [
            'listingModel' => $listingModel,
            'animalModel' => $animalModel,
            'existingImages' => $existingImages,
            'animalTypes' => $animalTypes,
            'breedsByType' => $breedsByType,
            'idades' => $idades,
            'portes' => $portes,
            'vacinas' => $vacinas,
            'users' => $users,
        ]);
    }

    public function actionDelete($id)
    {
        $model = $this->findModel($id);

         if ($model->animal) {
             $model->animal->status = Animal::STATUS_DELETED;
             $model->animal->save(false);
         }

        $model->status = Listing::STATUS_DELETED;
        $model->save(false);

        Yii::$app->session->setFlash('success', 'Anúncio e animal apagados com sucesso.');
        return $this->redirect(['index']);
    }

    protected function findModel($id)
    {
        if (($model = Listing::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionDeleteImage($id, $listing_id){
        $fileDb = File::findOne($id);

        if ($fileDb) {
            // Calcular caminho físico
            $frontendPath = dirname(dirname(__DIR__)) . '/frontend/web';
            $filePath = $frontendPath . $fileDb->path;

            //Apagar do disco
            if (file_exists($filePath)) {
                unlink($filePath);
            }

            //Apagar da Base de Dados
            $fileDb->delete();

            Yii::$app->session->setFlash('success', 'Imagem apagada com sucesso.');
        } else {
            Yii::$app->session->setFlash('error', 'Imagem não encontrada.');
        }

        //Redirecionar de volta para a página de edição
        return $this->redirect(['update', 'id' => $listing_id]);
    }
}