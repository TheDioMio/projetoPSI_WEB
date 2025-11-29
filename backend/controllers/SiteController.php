<?php

namespace backend\controllers;

use backend\models\AnimalSearch;
use common\models\Animal;
use common\models\Application;
use common\models\Listing;
use common\models\LoginForm;
use common\models\User;
use Yii;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\base\Action;

class SiteController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'denyCallback' => function () {
                    //se tiver acesso ao Backend redireciona para a home do back senão redireciona para para o login
                    if (Yii::$app->user->can('loginBackend')) {
                        return Yii::$app->response->redirect(['/site/index']);
                    }
                    return Yii::$app->response->redirect(['/site/login']);

                },
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['login', 'error'],
                    ],
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function actionError()
    {
        $exception = Yii::$app->errorHandler->exception;

        // Se NÃO estiver logado → redireciona para LOGIN
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['/site/login']);
        }

        return $this->render('error', [
            'exception' => $exception,
        ]);
    }

    public function beforeAction($action)
    {
        // 1. Executa a lógica padrão do Controller
        if (!parent::beforeAction($action)) {
            return false;
        }

        // 2. Lógica para passar o utilizador para o Layout
        // Usamos Yii::$app->user->identity que já é seguro (retorna null se não houver login)
        $currentUser = Yii::$app->user->identity;

        // 3. Define a variável global do Layout (View Params)
        // Se o utilizador não estiver logado, passamos null (o que deve ser tratado na sidebar)
        $this->view->params['userLogado'] = $currentUser;

        return true;
    }

    public function actionIndex()
    {
        $animais = Animal::find()->all();
        $utilizadores = User::find()->all();
        $listagens = Listing::find()->all();
        $candidaturas = Application::find()->all();
        return $this->render('index', [
            'animais'=>$animais,
            'utilizadores'=>$utilizadores,
            'listagens'=>$listagens,
            'candidaturas'=>$candidaturas,
        ]);
    }

//    public function actionLogin()
//    {
//        if (!Yii::$app->user->isGuest) {
//            return $this->goHome();
//        }
//
//        $this->layout = 'blank';
//
//        $model = new LoginForm();
//        if ($model->load(Yii::$app->request->post()) && $model->login()) {
//            return $this->goBack();
//        }
//
//        $model->password = '';
//
//        return $this->render('login', [
//            'model' => $model,
//        ]);
//    }
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            // Verifica permissão para acessar o backend
            if (Yii::$app->user->can('loginBackend')) {
                return $this->goHome();
            } else {
                Yii::$app->user->logout();
                Yii::$app->session->setFlash('error', 'You are not allowed to access the backend.');
                return $this->redirect(['site/login']);
            }
        }

        $this->layout = 'blank';

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {

            // Depois do login, verificar permissão
            if (Yii::$app->user->can('loginBackend')) {
                return $this->goBack();
            } else {
                //Não está a mostrar a mensagem -----------------------------!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
                Yii::$app->user->logout();
                Yii::$app->session->setFlash('error', 'You are not allowed to access the backend.');
                return $this->redirect(['site/login']);
            }
        }

        $model->password = '';

        return $this->render('login', [
            'model' => $model,
        ]);
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
}
