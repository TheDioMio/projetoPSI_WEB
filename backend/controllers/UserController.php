<?php

namespace backend\controllers;

use common\models\Application;
use common\models\Comment;
use common\models\Listing;
use common\models\Role;
use common\models\User;
use common\models\UserSearch;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use Yii;

class UserController extends Controller
{
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
                        'logout' => ['POST'],
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
        // Lógica para passar o utilizador para o LAYOUT/SIDEBAR
        $this->view->params['userLogado'] = Yii::$app->user->identity;

        return true;
    }
    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);
        $userLogado = Yii::$app->user->identity;

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'userLogado' => $userLogado,
        ]);
    }

    public function actionView($id)
    {
        $model = $this->findModel($id);
        $totalUserApplications = $model->getApplications()->count();
        $totalUserAnimais = $model->getAnimals()->count();

        /*PARA CARREGAR A IMAGEM DO USER PARA O VIEW*/
        /* 1. Trocar os links, igual ao animal view, o link das imagens vem do frontend
        e temos que mudar o link para vir do backend*/
        $backendBaseUrl = Yii::$app->request->baseUrl; // /projeto/backend/web
        $frontendBaseUrl = str_replace('/backend/web', '/frontend/web', $backendBaseUrl); // /projeto/frontend/web
        $avatar = '';
        //2. Carregar a foto do user, concatenação para conseguirmos o URL certo
        if ($model->profileImage) {
            $avatar = $frontendBaseUrl . '/' . ltrim($model->profileImage->path, '/');
        }

        return $this->render('view', [
            'model' => $model,
            'totalUserApplications' => $totalUserApplications,
            'totalUserAnimais' => $totalUserAnimais,
            'avatar' => $avatar,
        ]);
    }

    public function actionCreate()
    {
        $model = new User();
        $model->scenario = 'create'; // ativa a regra da senha
        $roles = Role::find()->select(['id','description'])->indexBy('id')->asArray()->all();
        if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post())) {
            // Gera hash com a senha digitada
            $model->setPassword($model->password);
//            $model->generateAuthKey();

            if ($model->save()) {
                $auth = Yii::$app->authManager;
                $roleMap = [
                    1 => 'admin',
                    2 => 'userPro',
                    3 => 'user',
                ];
                // Verifica se o ID que veio do form (ex. 2) existe no nosso mapa
                if (isset($roleMap[$model->role_id])) {
                    $roleName = $roleMap[$model->role_id];
                    $authorRole = $auth->getRole($roleName);
                    if ($authorRole) {
                        $auth->assign($authorRole, $model->id);
                    }
                }

                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('create', [
            'model' => $model,
            'roles' => $roles,
        ]);
    }

    public function actionUpdate($id) {
        $model = $this->findModel($id);
        $oldPasswordHash = $model->password_hash;
        $roles = Role::find()->select(['id','description'])->indexBy('id')->asArray()->all();
        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            if (!empty($model->password)) {
                $model->setPassword($model->password); // este método gera o hash
            } else {
                // Se vier vazio, mantém o hash antigo
                $model->password_hash = $oldPasswordHash;
            }
            if ($model->save()) {
                $auth = Yii::$app->authManager;
                $auth->revokeAll($model->id); // Limpa anteriores

                $roleMap = [
                    1 => 'admin',
                    2 => 'userPro',
                    3 => 'user',
                ];
                // Verifica se o ID que veio do form (ex. 2) existe no nosso mapa
                if (isset($roleMap[$model->role_id])) {
                    $roleName = $roleMap[$model->role_id];
                    $authorRole = $auth->getRole($roleName);
                    if ($authorRole) {
                        $auth->assign($authorRole, $model->id);
                    }
                }
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('update', [
            'model' => $model,
            'roles' => $roles,
        ]);
    }

    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        $model->status = User::STATUS_DELETED;
        $model->save(false);

        return $this->redirect(['index']);
    }

    protected function findModel($id)
    {
        if (($model = User::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionUpdateStatus($id){
        $model = $this->findModel($id);

        if ($model->status == 10) {
            $model->status = 9;
            Yii::$app->session->setFlash('warning', 'Utilizador desativado!');
        } else {
            $model->status = 10;
            Yii::$app->session->setFlash('success', 'Utilizador ativado!');
        }

        $model->save(false); //tem que estar false, se não explode

        //Este redirect é feito um pouco mais "estranho" do que o normal para preservar filtros que estejam aplicados
        return $this->redirect(Yii::$app->request->referrer ?: ['index']);
    }
}
