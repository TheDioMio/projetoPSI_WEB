<?php

namespace backend\controllers;

use common\models\Application;
use app\models\ApplicationSearch;
use common\models\User;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

class ApplicationController extends Controller
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
        $searchModel = new ApplicationSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        //Query que vai buscar todas as aplicações que tenham o type_user_pro e que o status seja 0
        $queryUserPro = Application::find()
            ->joinWith(['candidate'])
            ->where(['type' => Application::TYPE_USER_PRO])
            ->andWhere(['application.status' => 0]);



        $pendingUserProApplications = new ActiveDataProvider([
            'query' => $queryUserPro,
            'sort' => [
                'defaultOrder' => ['created_at' => SORT_DESC],
                'attributes' => [
                    'created_at',
                    'description',
                    'candidate_name' => [
                        'asc' => ['user.name' => SORT_ASC],
                        'desc' => ['user.name' => SORT_DESC],
                    ],
                ],
            ],
        ]);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'pendingUserProApplications' => $pendingUserProApplications,
        ]);
    }

    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionDenyApplication($id) {
        $model = $this->findModel($id);

        //Status da candidatura= 0 => pending, 1 => denied, 2 => accepted.
        $statusDenied = '1';
        $model->status = $statusDenied;

        $model->save(false); //tem que estar false, se não explode

        //Depois da aplicação ser negada, dar redirect para o index.
        return $this->redirect(['index']);
    }

    public function actionAcceptApplication($id) {
        $model = $this->findModel($id);
        $model->status = 2;
        // Aceder ao candidato (User) através da relação
        $candidate = $model->candidate;

        // Só prossegue se conseguir guardar a candidatura EEEEEEE se o candidato existir
        if ($candidate && $model->save(false)) {
            $candidate->role_id = 2;

            // Guardamos o User (false para saltar validações de password/imagem que não interessam agora)
            if ($candidate->save(false)) {
                $auth = Yii::$app->authManager;
                $auth->revokeAll($candidate->id);
                $roleMap = [
                    1 => 'admin',
                    2 => 'userPro',
                    3 => 'user',
                ];

                if (isset($roleMap[$candidate->role_id])) {
                    $roleName = $roleMap[$candidate->role_id];
                    $authorRole = $auth->getRole($roleName);

                    if ($authorRole) {
                        $auth->assign($authorRole, $candidate->id);
                    }
                }
                Yii::$app->session->setFlash('success', 'Candidatura aceite. Utilizador promovido a UserPro.');
            }
        } else {
            Yii::$app->session->setFlash('error', 'Erro ao aceitar a candidatura.');
            $model->status = 0;
        }
        return $this->redirect(['index']);
    }

    public function actionViewUserPro($id)
    {
        return $this->render('view-user-pro', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionCreate()
    {
        $model = new Application();

        $users = User::find()->select(['id','username'])->indexBy('id')->asArray()->all();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
            'users' => $users,
        ]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    protected function findModel($id)
    {
        if (($model = Application::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
