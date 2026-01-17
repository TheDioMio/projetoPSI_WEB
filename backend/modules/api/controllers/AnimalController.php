<?php

namespace backend\modules\api\controllers;

use backend\modules\api\models\Animal;
use backend\modules\api\models\Comment;
use backend\modules\api\models\File;
use backend\modules\api\models\User;
use common\models\Listing;
use common\models\AnimalType;
use common\models\Breed;
use common\models\AnimalAge;
use common\models\AnimalSize;
use common\models\Vaccination;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\auth\QueryParamAuth;
use yii\filters\VerbFilter;
use yii\rest\ActiveController;
use yii\web\BadRequestHttpException;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\ServerErrorHttpException;


/**
 * Default controller for the `api` module
 */
class AnimalController extends ActiveController
{
    public $modelClass = 'backend\modules\api\models\Animal';


    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::className(),
        ];

        $behaviors['verbs'] = [
            'class' => VerbFilter::class,
            'actions' => [
                'index'      => ['GET'],
                'view'       => ['GET'],
                'create'     => ['POST'],
                'update'     => ['PUT', 'PATCH'],
                'delete'     => ['DELETE'],
                'myanimals'  => ['GET'],
                'edit'       => ['GET'],
                'meta'       => ['GET'],
                'add-view'   => ['POST'],
                'stats' => ['GET'],
            ],
        ];

        return $behaviors;
    }

    public function beforeAction($action)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        return parent::beforeAction($action);
    }

    //Utilizo para subescrever
    public function actions()
    {
        $actions = parent::actions();
        unset($actions['create']);
        unset($actions['view']);
        unset($actions['update']);
        unset($actions['delete']);

        return $actions;
    }


     /**
     * GET /animals
     *
     * Lista animais com anúncio ativo, com paginação e possibilidade de filtros opcionais.
     *
     * Query params opcionais:
     * - type: filtra por tipo de animal (campo "type" no modelo).
     * - size: filtra por tamanho (campo "size" no modelo).
     *
     * Respostas:
     * - 200: retorna um objeto paginado (ActiveDataProvider) com animais e relações
     *        (files, listing, comments, user, etc.).
     */
    public function actionIndex()
    {
        if (!Yii::$app->user->can('animalsManager')) {
            throw new ForbiddenHttpException('Sem permissão para listar animais');
        }

        $query = Animal::find()
            ->where(['status' => Listing::STATUS_ACTIVE])
            ->with('files',
                'listing',
                'listing.comments.user.profileImage',
                'breed',
                'animalType',
                'animalAge',
                'size',
                'vaccination',
                'user');

        // filtros opcionais (ex: type, size)
        $request = \Yii::$app->request;

        if ($type = $request->get('type')) {
            $query->andWhere(['type' => $type]);
        }

        if ($size = $request->get('size')) {
            $query->andWhere(['size' => $size]);
        }

        return new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
                'pageSizeLimit' => [5, 50],
            ],
            'sort' => [
                'defaultOrder' => [
                    'created_at' => SORT_DESC,
                ],
            ],
        ]);
    }


    /**
     * POST /animals
     *
     * Cria um novo animal e o respetivo anúncio (listing) para o utilizador autenticado.
     *
     * Body (JSON):
     * - Campos do Animal (ex.: name, description, location, animal_type_id, breed_id,
     *   age_id, size_id, vaccination_id, neutered, ...).
     * - listing_description: descrição do anúncio.
     * - listing_status: estado inicial do anúncio (ex.: ativo/inativo).
     *
     * Regras:
     * - O utilizador tem de estar autenticado (Bearer Token).
     *
     * Respostas:
     * - 201: { "success": true, "animal_id": <int> } em caso de sucesso.
     * - 400: erros de validação do Animal ou Listing.
     * - 401: se não houver autenticação válida.
     */
    public function actionCreate()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        if (!Yii::$app->user->can('animalsManager')) {
            throw new ForbiddenHttpException('Sem permissão para criar animais');
        }

        $userId = Yii::$app->user->id;
        $data = Yii::$app->request->bodyParams;

        $transaction = Yii::$app->db->beginTransaction();

        try {

            // 1️⃣ Animal
            $animal = new Animal();
            $animal->scenario = Animal::SCENARIO_API_CREATE;
            $animal->load($data, '');
            $animal->user_id = $userId;
            $animal->status = $data['listing_status'] ?? 0;

            if (!$animal->save()) {
                throw new BadRequestHttpException(json_encode($animal->errors));
            }

            // 2️⃣ Listing
            $listing = new Listing();
            $listing->animal_id = $animal->id;
            $listing->user_id = $userId;
            $listing->description = $data['listing_description'] ?? '';
            $listing->status = $data['listing_status'] ?? 0;
            $listing->views = 0;
            $listing->created_at = date('Y-m-d H:i:s');

            if (!$listing->save()) {
                throw new BadRequestHttpException(json_encode($listing->errors));
            }

            $transaction->commit();

            Yii::$app->response->statusCode = 201;

            return [
                'success' => true,
                'animal_id' => $animal->id
            ];

        } catch (\Throwable $e) {
            $transaction->rollBack();
            throw $e;
        }
    }


    /**
     * PUT /animals/{id}
     * PATCH /animals/{id}
     *
     * Atualiza um animal existente e o seu anúncio, pertencente ao utilizador autenticado.
     *
     * Path params:
     * - id: ID do animal a atualizar.
     *
     * Body (JSON):
     * - Campos do Animal a alterar.
     * - listing_description (opcional).
     * - listing_status (opcional).
     *
     * Regras:
     * - Só o dono do animal (user_id) pode atualizar.
     *
     * Respostas:
     * - 200: { "success": true } em caso de sucesso.
     * - 400: erros de validação.
     * - 403: se o animal não pertencer ao utilizador autenticado.
     * - 404: se o animal não existir.
     */
    public function actionUpdate($id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        if (!Yii::$app->user->can('animalsManager')) {
            throw new ForbiddenHttpException('Sem permissão para editar animais');
        }

        $userId = Yii::$app->user->id;
        $data = Yii::$app->request->bodyParams;

        $animal = Animal::findOne($id);

        if (!$animal) {
            throw new NotFoundHttpException('Animal não encontrado');
        }

        if ($animal->user_id !== $userId) {
            throw new ForbiddenHttpException('Não autorizado');
        }

        $transaction = Yii::$app->db->beginTransaction();

        try {
            // 1️⃣ Animal
            $animal->load($data, '');
            $animal->scenario = Animal::SCENARIO_API_UPDATE;
            $animal->status = $data['listing_status'] ?? 0;
            if (!$animal->save()) {
                throw new BadRequestHttpException(json_encode($animal->errors));
            }

            // 2️⃣ Listing
            $listing = Listing::findOne(['animal_id' => $animal->id]);
            if ($listing) {
                $listing->description = $data['listing_description'] ?? $listing->description;
                $listing->status = $data['listing_status'] ?? $listing->status;
                if (!$listing->save()) {
                    throw new BadRequestHttpException(json_encode($listing->errors));
                }

            }

            $transaction->commit();

            return [
                'success' => true
            ];

        } catch (\Throwable $e) {
            $transaction->rollBack();
            throw $e;
        }
    }

    /**
     * GET /animals/my
     *
     * Devolve apenas os animais com anúncio ativo pertencentes ao utilizador autenticado.
     *
     * Características:
     * - Sem paginação (retorna todos os animais do utilizador).
     * - Inclui relações como files, listing, comments, etc.
     *
     * Respostas:
     * - 200: ActiveDataProvider (sem paginação) com os animais do utilizador.
     * - 401: se não houver autenticação válida.
     */
    public function actionMyanimals()
    {
        if (!Yii::$app->user->can('animalsManager')) {
            throw new ForbiddenHttpException('Sem permissão para listar os próprios animais');
        }

        $userId = \Yii::$app->user->id;

        $query = Animal::find()
            ->where([
                'user_id' => $userId
            ])
            ->with(
                'files',
                'listing',
                'listing.comments.user.profileImage',
                'breed',
                'animalType',
                'animalAge',
                'size',
                'vaccination',
                'user'
            );

        return new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
            'sort' => [
                'defaultOrder' => [
                    'created_at' => SORT_DESC,
                ],
            ],
        ]);
    }

    /**
     * GET /animals/{id}/edit
     *
     * Devolve dados de um animal do utilizador autenticado preparados para edição.
     *
     * Path params:
     * - id: ID do animal.
     *
     * Retorna:
     * - Dados base do animal (id, name, description, location, ...).
     * - IDs de relações (type_id, breed_id, age_id, size_id, vaccination_id).
     * - Informação se é neutered.
     * - Dados do listing associado.
     * - Ficheiros (files) associados.
     *
     * Respostas:
     * - 200: JSON com os dados do animal e relações.
     * - 404: se não existir ou não pertencer ao utilizador autenticado.
     */
    public function actionEdit($id)
    {
        if (!Yii::$app->user->can('animalsManager')) {
            throw new ForbiddenHttpException('Sem permissão para ver animal para edição.');
        }

        $userId = Yii::$app->user->id;

        $animal = Animal::find()
            ->where(['id' => $id, 'user_id' => $userId])
            ->one();

        if (!$animal) {
            throw new NotFoundHttpException('Animal não encontrado');
        }

        return [
            'id' => $animal->id,
            'name' => $animal->name,
            'description' => $animal->description,
            'location' => $animal->location,

            'type_id' => $animal->animal_type_id,
            'breed_id' => $animal->breed_id,
            'age_id' => $animal->age_id,
            'size_id' => $animal->size_id,
            'vaccination_id' => $animal->vaccination_id,
            'neutered' => $animal->neutered,
            'listing' => $animal->listing,

            'files' => $animal->files
        ];
    }


    /**
     * GET /animals/{id}
     *
     * Devolve os detalhes de um animal pertencente ao utilizador autenticado.
     *
     * Path params:
     * - id: ID do animal.
     *
     * Retorna:
     * - Campos do animal.
     * - IDs e descrições de tipo, raça, idade, tamanho e vacinação.
     * - Ficheiros associados (files).
     * - Comentários do anúncio (comments) se existir listing.
     *
     * Respostas:
     * - 200: JSON com dados completos do animal e relações.
     * - 404: se o animal não existir ou não pertencer ao utilizador autenticado.
     */
    public function actionView($id)
    {
        if (!Yii::$app->user->can('animalsManager')) {
            throw new ForbiddenHttpException('Sem permissão para consultar animal.');
        }

        $userId = Yii::$app->user->id;

        $animal = Animal::find()
            ->where(['id' => $id, 'user_id' => $userId])
            ->with(
                'files',
                'listing.comments.user.profileImage',
                'breed',
                'animalType',
                'animalAge',
                'size',
                'vaccination'
            )
            ->one();

        if (!$animal) {
            throw new NotFoundHttpException('Animal não encontrado');
        }

        return array(
            'id' => $animal->id,
            'name' => $animal->name,
            'description' => $animal->description,
            'location' => $animal->location,

            // IDs (para spinners)
            'animal_type_id' => $animal->animal_type_id,
            'breed_id' => $animal->breed_id,
            'age_id' => $animal->age_id,
            'size_id' => $animal->size_id,
            'vaccination_id' => $animal->vaccination_id,
            'neutered' => $animal->neutered,

            // descrições (opcional mas útil)
            'type' => $animal->animalType ? $animal->animalType->description : null,
            'breed' => $animal->breed ? $animal->breed->description : null,
            'age' => $animal->animalAge ? $animal->animalAge->description : null,
            'size' => $animal->size ? $animal->size->description : null,
            'vaccination' => $animal->vaccination ? $animal->vaccination->description : null,

            'files' => $animal->files,
            'comments' => $animal->listing ? $animal->listing->comments : [],

        );
    }


    /**
     * DELETE /animals/{id}
     *
     * Apaga um animal do utilizador autenticado, o anúncio associado, comentários
     * e ficheiros (registos na base de dados e ficheiros físicos).
     *
     * Path params:
     * - id: ID do animal a apagar.
     *
     * Regras:
     * - Só o dono do animal (user_id) pode apagar.
     * - A operação é feita dentro de uma transação.
     *
     * Respostas:
     * - 200: { "success": true, "message": "Animal e dados associados apagados com sucesso" }.
     * - 403: se o animal não pertencer ao utilizador autenticado.
     * - 404: se o animal não existir.
     * - 500: se ocorrer erro ao apagar comments/files/listing/animal.
     */
    public function actionDelete($id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        if (!Yii::$app->user->can('animalsManager')) {
            throw new ForbiddenHttpException('Sem permissão para apagar animais');
        }

        $userId = Yii::$app->user->id;

        $transaction = Yii::$app->db->beginTransaction();

        try {
            $animal = Animal::findOne($id);

            if (!$animal) {
                throw new NotFoundHttpException('Animal não encontrado');
            }

            if ($animal->user_id !== $userId) {
                throw new ForbiddenHttpException('Não autorizado');
            }

            // 🔹 Listing
            $listing = Listing::find()->where(['animal_id' => $animal->id])->one();

            if ($listing) {
                // 🔹 Apagar comments
                Comment::deleteAll(['listing_id' => $listing->id]);
            }

            // 🔹 Apagar files ANTES do animal

            $files = File::find()->where(['animal_id' => $animal->id])->all();

            foreach ($files as $file) {
                // $file->path tem o mesmo formato que em create: '/uploads/animals/{id}/{ficheiro}'
                $fullPath = Yii::getAlias('@frontend/web/' . ltrim($file->path, '/'));

                if (file_exists($fullPath)) {
                    if (!@unlink($fullPath)) {
                        Yii::error('Falha ao apagar ficheiro físico: ' . $fullPath, 'DELETE_DEBUG');
                    }
                } else {
                    Yii::warning('Ficheiro não encontrado: ' . $fullPath, 'DELETE_DEBUG');
                }

                if ($file->delete() === false) {
                    throw new ServerErrorHttpException('Erro ao apagar file ID ' . $file->id);
                }
            }



            // 🔹 Apagar listing
            if ($listing) {
                $listing->delete();
            }

            // 🔹 Apagar animal (AGORA SIM)
            $animal->delete();

            $transaction->commit();

            return [
                'success' => true,
                'message' => 'Animal e dados associados apagados com sucesso'
            ];

        } catch (\Throwable $e) {
            $transaction->rollBack();

            Yii::error($e->getMessage(), __METHOD__);

            throw new ServerErrorHttpException(
                'Erro ao apagar animal'
            );
        }
    }

    /**
     * GET /api/animal/meta
     *
     * Devolve dados auxiliares para preenchimento de formulários (combobox/spinners).
     *
     * Retorna:
     * - types: lista de tipos de animal (id, description).
     * - breeds: lista de raças (id, description, animal_type_id).
     * - ages: faixas etárias (id, description).
     * - sizes: tamanhos (id, description).
     * - vaccinations: tipos de vacinação (id, description).
     *
     * Respostas:
     * - 200: JSON com arrays de cada entidade.
     */
    public function actionMeta()
    {
        if (!Yii::$app->user->can('animalsManager')) {
            throw new ForbiddenHttpException('Sem permissão para listar tabelas auxiliares.');
        }

        return [
            'types' => AnimalType::find()
                ->select(['id', 'description'])
                ->orderBy('description')
                ->asArray()
                ->all(),

            'breeds' => Breed::find()
                ->select(['id', 'description', 'animal_type_id'])
                ->orderBy('description')
                ->asArray()
                ->all(),

            'ages' => AnimalAge::find()
                ->select(['id', 'description'])
                ->orderBy('description')
                ->asArray()
                ->all(),

            'sizes' => AnimalSize::find()
                ->select(['id', 'description'])
                ->orderBy('description')
                ->asArray()
                ->all(),

            'vaccinations' => Vaccination::find()
                ->select(['id', 'description'])
                ->orderBy('description')
                ->asArray()
                ->all(),


        ];
    }

    /**
     * POST /api/animals/{id}/view
     *
     * Incrementa o número de visualizações do anúncio associado ao animal.
     */
    public function actionAddView($id)
    {
        $animal = Animal::find()
            ->where(['id' => $id])
            ->with('listing')
            ->one();

        if (!$animal || !$animal->listing) {
            throw new NotFoundHttpException('Anúncio não encontrado');
        }

        // Incremento seguro
        $animal->listing->updateCounters(['views' => 1]);

        return [
            'success' => true,
            'views' => $animal->listing->views
        ];
    }


    public function actionStats()
    {
        if (!Yii::$app->user->can('animalsManager')) {
            throw new ForbiddenHttpException('Sem permissão');
        }

        // Animais adotados
        $animalsAdopted = Animal::find()
            ->where(['status' => Listing::STATUS_ADOPTED])
            ->count();

        // Animais à espera
        $animalsWaiting = Animal::find()
            ->where(['status' => Listing::STATUS_ACTIVE])
            ->count();

        // Utilizadores ativos (user + userPro)
        $activeUsers = User::find()
            ->where(['status' => User::STATUS_ACTIVE])
            ->andWhere(['in', 'role_id', [2, 3]])
            ->count();

        // Anúncios ativos
        $activeListings = Listing::find()
            ->where(['status' => Listing::STATUS_ACTIVE])
            ->count();

        // Total de views
        $totalViews = Listing::find()
            ->sum('views');

        return [
            'success' => true,
            'animals_adopted' => (int)$animalsAdopted,
            'animals_waiting' => (int)$animalsWaiting,
            'active_users' => (int)$activeUsers,
            'active_listings' => (int)$activeListings,
            'total_views' => (int)$totalViews,
        ];
    }

}
