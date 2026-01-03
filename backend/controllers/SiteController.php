<?php
namespace backend\controllers;

use app\models\ApplicationSearch;
use backend\models\AnimalSearch;
use backend\mosquitto\MosquittoCatcher;
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
                    //se tiver acesso ao Backend redireciona para a home do back se não, redireciona para para o login
                    if (Yii::$app->user->can('loginBackend')) {
                        return Yii::$app->response->redirect(['/site/index']);
                    }
                    return Yii::$app->response->redirect(['/site/login']);

                },
                'except' => ['error'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['login'],
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

    public function actionIndex() {
        $animais = Animal::find()->all();
        $utilizadores = User::find()->all();
        $listagens = Listing::find()->all();
        $candidaturas = Application::find()->all();
        $animaisRecentes = Animal::find()
            ->with(['animalType', 'breed'])
            ->orderBy(['created_at' => SORT_DESC]) // Ou 'id' => SORT_DESC
            ->limit(5)
            ->all();

        //Candidaturas Pendentes
        $searcher = New ApplicationSearch();
        $candidaturasPendentes = $searcher->searchPendingAdoption()->getModels();

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

    public function actionStatistics() {

        $user = Yii::$app->user->identity;

        /*PARA CARREGAR A IMAGEM DOS ANIMAIS PARA A VIEW*/
        $user = Yii::$app->user->identity;
        $backendBaseUrl = Yii::$app->request->baseUrl;
        $frontendBaseUrl = str_replace('/backend/web', '/frontend/web', $backendBaseUrl);
        $avatar = null;
        if ($user && $user->profileImage) {
            $avatar = $frontendBaseUrl . '/' . ltrim($user->profileImage->path, '/');
        }



        // =========================================================
        // 1. KPIS GERAIS
        // =========================================================
        $totalUsers   = (int)User::find()->count();
        $totalAnimals = (int)Animal::find()->count();
        $totalApps    = (int)Application::find()->count();

        // =========================================================
        // 2. EVOLUÇÃO MENSAL
        // =========================================================
        $sixMonthsAgo = date('Y-m-01', strtotime('-5 months'));
        $appsPorMes = Application::find()->select(["DATE_FORMAT(created_at, '%Y-%m') as month", "COUNT(*) as total"])
            ->where(['>=', 'created_at', $sixMonthsAgo])->groupBy('month')->orderBy('month ASC')->asArray()->all();
        $animaisPorMes = Animal::find()->select(["DATE_FORMAT(created_at, '%Y-%m') as month", "COUNT(*) as total"])
            ->where(['>=', 'created_at', $sixMonthsAgo])->groupBy('month')->orderBy('month ASC')->asArray()->all();

        $trendLabels = []; $trendDataApps = []; $trendDataAnimals = [];
        for ($i = 5; $i >= 0; $i--) {
            $m = date('Y-m', strtotime("-$i months"));
            $trendLabels[] = date('M Y', strtotime("-$i months"));

            $fApp = array_filter($appsPorMes, fn($r) => $r['month'] == $m);
            $trendDataApps[] = !empty($fApp) ? (int)array_values($fApp)[0]['total'] : 0;

            $fAni = array_filter($animaisPorMes, fn($r) => $r['month'] == $m);
            $trendDataAnimals[] = !empty($fAni) ? (int)array_values($fAni)[0]['total'] : 0;
        }

        // =========================================================
        // 3. PERFIL HABITAÇÃO (JSON)
        // =========================================================
        $candidaturasAdocao = Application::find()->where(['type' => Application::TYPE_ADOPTION])->all();
        $statsHabitacao = ['Própria' => 0, 'Arrendada (Permite)' => 0, 'Arrendada (Não Permite)' => 0];
        foreach ($candidaturasAdocao as $app) {
            $data = is_string($app->data) ? json_decode($app->data, true) : $app->data;
            if (isset($data['home'])) {
                switch ((int)$data['home']) {
                    case 1: $statsHabitacao['Própria']++; break;
                    case 2: $statsHabitacao['Arrendada (Permite)']++; break;
                    case 3: $statsHabitacao['Arrendada (Não Permite)']++; break;
                }
            }
        }

        // =========================================================
        // 4. SAÚDE, LOCALIZAÇÃO E VACINAÇÃO
        // =========================================================
        $vacStats = Animal::find()->select(['vaccination_id', 'COUNT(*) as total'])->groupBy('vaccination_id')->asArray()->all();
        $vacData = [0, 0, 0];
        foreach ($vacStats as $s) { if ($s['vaccination_id'] >= 1 && $s['vaccination_id'] <= 3) $vacData[$s['vaccination_id'] - 1] = (int)$s['total']; }

        $topLocais = Animal::find()->select(['location', 'COUNT(*) as total'])
            ->where(['IS NOT', 'location', null])->andWhere(['!=', 'location', ''])->groupBy('location')
            ->orderBy(['total' => SORT_DESC])->limit(5)->asArray()->all();
        $locLabels = array_column($topLocais, 'location');
        $locData   = array_column($topLocais, 'total');

        $topVistos = Listing::find()->with(['animal', 'animal.animalType'])->orderBy(['views' => SORT_DESC])->limit(5)->all();

        // =========================================================
        // 5. DEMOGRAFIA ETÁRIA & RAÇAS
        // =========================================================
        $ageStats = Animal::find()->alias('a')->select(['animal_age.description', 'COUNT(a.id) as total'])
            ->joinWith('animalAge')->groupBy('a.age_id')->asArray()->all();
        $ageLabels = array_column($ageStats, 'description');
        $ageData = array_column($ageStats, 'total');

        $breedStats = Animal::find()->alias('a')->select(['b.description', 'COUNT(a.id) as total'])
            ->joinWith('breed b')->groupBy('a.breed_id')->orderBy(['total' => SORT_DESC])->limit(8)->asArray()->all();
        $breedLabels = array_column($breedStats, 'description');
        $breedData = array_column($breedStats, 'total');


        // =========================================================
        // 6. NOVAS ESTATÍSTICAS (ESTERILIZAÇÃO, USERS, STATUS APP)
        // =========================================================

        // A. Esterilização (0=Não, 1=Sim)
        $neuteredStats = Animal::find()->select(['neutered', 'COUNT(*) as total'])->groupBy('neutered')->asArray()->all();
        $neuteredData = [0, 0]; // [Não, Sim]
        foreach ($neuteredStats as $n) {
            if ($n['neutered'] == 0) $neuteredData[0] = (int)$n['total'];
            if ($n['neutered'] == 1) $neuteredData[1] = (int)$n['total'];
        }

        // B. Tipos de Utilizador (Roles)
        $roleStats = User::find()->alias('u')->select(['r.description', 'COUNT(u.id) as total'])
            ->joinWith('role r')->groupBy('u.role_id')->asArray()->all();
        $roleLabels = array_column($roleStats, 'description');
        $roleData = array_column($roleStats, 'total');

        // C. Status de Candidaturas
        $sent = Application::STATUS_SENT;
        $inReview = Application::STATUS_IN_REVIEW;
        $approved = Application::STATUS_APPROVED;
        $rejected = Application::STATUS_REJECTED;

        $appStatusStats = Application::find()->select(['status', 'COUNT(*) as total'])->groupBy('status')->orderBy('status')->asArray()->all();
        $statusLabelsMap = [$sent => 'Enviado', $inReview => 'Em Análise', $approved => 'Aprovado', $rejected => 'Rejeitado'];
        $appStatusLabels = [];
        $appStatusData = [];

        foreach ($appStatusStats as $s) {
            $appStatusLabels[] = $statusLabelsMap[$s['status']] ?? 'Outro';
            $appStatusData[] = (int)$s['total'];
        }

        return $this->render('statistics', [
            'totalUsers' => $totalUsers, 'totalAnimals' => $totalAnimals, 'totalApps' => $totalApps,
            'trendLabels' => $trendLabels, 'trendDataApps' => $trendDataApps, 'trendDataAnimals' => $trendDataAnimals,
            'statsHabitacao' => array_values($statsHabitacao), 'vacData' => $vacData,
            'locLabels' => $locLabels, 'locData' => $locData, 'topVistos' => $topVistos,
            'ageLabels' => $ageLabels, 'ageData' => $ageData, 'breedLabels' => $breedLabels, 'breedData' => $breedData,
            'neuteredData' => $neuteredData, 'avatar' => $avatar,
            'roleLabels' => $roleLabels, 'roleData' => $roleData,
            'appStatusLabels' => $appStatusLabels, 'appStatusData' => $appStatusData
        ]);
    }

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

//    public function actionTestarMqtt()
//    {
//        $topico = "teste/yii2";
//        $mensagem = "Olá! Isto foi enviado pelo Yii2 às " . date('H:i:s');
//
//        try {
//            MosquittoCatcher::makePublish($topico, $mensagem);
//            return "Mensagem enviada com sucesso para o tópico: " . $topico;
//        } catch (\Exception $e) {
//            return "Erro: " . $e->getMessage();
//        }
//    }


    /******** Temporario ***************************************************/
}
