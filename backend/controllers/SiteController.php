<?php
namespace backend\controllers;

use backend\models\AnimalSearch;
use common\models\Animal;
use common\models\Application;
use common\models\Listing;
use common\models\LoginForm;
use common\models\User;
use MosquittoCatcher;
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
                    //se tiver acesso ao Backend redireciona para a home do back se não, redireciona para para o login
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
        $animaisRecentes = Animal::find()
            ->with(['animalType', 'breed'])
            ->orderBy(['created_at' => SORT_DESC]) // Ou 'id' => SORT_DESC
            ->limit(5)
            ->all();

        //Candidaturas Pendentes (Considerei que status = 0 é 'pendente')
        $candidaturasPendentes = Application::find()
            ->where(['status' => '0'])
            ->orderBy(['created_at' => SORT_DESC])
            ->limit(4)
            ->all();

        //Hardcoded (1 = Cão, 2 = Gato, 3 = Outros) conforme a tabela animal_type
        $totalCaes = Animal::find()->where(['animal_type_id' => 1])->count();
        $totalGatos = Animal::find()->where(['animal_type_id' => 2])->count();
        $totalOutros = Animal::find()->where(['animal_type_id' => 3])->count();

        $total = $totalCaes + $totalGatos + $totalOutros;
        $percentagemCaes = $total > 0 ? ($totalCaes / $total) * 100 : 0;
        $percentagemGatos = $total > 0 ? ($totalGatos / $total) * 100 : 0;
        $percentagemOutros = $total > 0 ? ($totalOutros / $total) * 100 : 0;


        return $this->render('index', [
            'animais'=>$animais,
            'utilizadores'=>$utilizadores,
            'listagens'=>$listagens,
            'candidaturas'=>$candidaturas,
            'animaisRecentes'=>$animaisRecentes,
            'candidaturasPendentes' => $candidaturasPendentes,
            'percentagemCaes' => $percentagemCaes,
            'percentagemGatos' => $percentagemGatos,
            'percentagemOutros' => $percentagemOutros,
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





    /******** Temporario ***************************************************/

    public function actionTestarMqtt()
    {
        $topico = "teste/yii2";
        $mensagem = "Olá! Isto foi enviado pelo Yii2 às " . date('H:i:s');

        try {
            // Se usaste a classe estática original:
            MosquittoCatcher::makePublish($topico, $mensagem);

            // Se configuraste como componente (Yii::$app->mqtt->publish...), usa esse método.

            return "Mensagem enviada com sucesso para o tópico: " . $topico;
        } catch (\Exception $e) {
            return "Erro: " . $e->getMessage();
        }
    }


    /******** Temporario ***************************************************/
}
