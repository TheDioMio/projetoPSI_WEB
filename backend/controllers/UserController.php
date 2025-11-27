<?php

namespace backend\controllers;

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
        $searchModel = new UserSearch();
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
        $model = new User();
        $model->scenario = 'create'; // ativa a regra da senha
        $roles = Role::find()->select(['id','description'])->indexBy('id')->asArray()->all();
        if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post())) {
            // Gera hash com a senha digitada
            $model->setPassword($model->password);
//            $model->generateAuthKey();

            if ($model->save()) {
                $auth = Yii::$app->authManager;
                /*Para atribuição de roles, POR AGORA, temos de fazer o mapeamento manual, já que na nossa tabela
                auth_item temos os roles escritos de forma diferente aos da tabela roles (ex. "Admin", "Administrator").
                */
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

    public function actionUpdate($id)
    {
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

                /*Para atribuição de roles, POR AGORA, temos de fazer o mapeamento manual, já que na nossa tabela
                auth_item temos os roles escritos de forma diferente aos da tabela roles (ex. "Admin", "Administrator").
                */
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
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    protected function findModel($id)
    {
        if (($model = User::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
