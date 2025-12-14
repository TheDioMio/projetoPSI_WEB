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
use yii\helpers\Html;

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
        $queryParams = $this->request->queryParams;
        $dataProvider = $searchModel->search($this->request->queryParams);
        //Isto aqui faz com que o queryParams abranja só as candidaturas com o cenário TYPE_ADOPTION
        $queryParams['ApplicationSearch']['type'] = Application::TYPE_ADOPTION;
        $dataProvider = $searchModel->search($queryParams);

        //Query que vai buscar todas as aplicações que tenham o type_adoption e que o status seja 0
        $queryAdoption = Application::find()
            ->joinWith(['candidate'])
            ->where(['type' => Application::TYPE_ADOPTION])
            ->andWhere(['application.status' => 0]);

        $pendingAdoptionApplications = new ActiveDataProvider([
            'query' => $queryAdoption,
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
            'pendingAdoptionApplications' => $pendingAdoptionApplications,
        ]);
    }

    public function actionIndexUserPro() {
        $searchModel = new ApplicationSearch();
        $queryParams = $this->request->queryParams;

        //Isto aqui faz com que o queryParams abranja só as candidaturas com o cenário TYPE_USER_PRO
        $queryParams['ApplicationSearch']['type'] = Application::TYPE_USER_PRO;
        $dataProvider = $searchModel->search($queryParams);

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

        return $this->render('index-user-pro', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'pendingUserProApplications' => $pendingUserProApplications,
        ]);
    }

    public function actionView($id)
    {
        $model = $this->findModel($id);
        // 1. Processar o Status
        $statusData = $this->getStatusInfo($model->status);

        // 2. Processar os dados do JSON
        $jsonAttributes = $this->getFormattedJsonDataAdoption($model->data);

        return $this->render('view', [
            'model' => $model,
            'statusLabel' => $statusData['label'], // Passamos o texto (EX. Pendente)
            'statusClass' => $statusData['class'], // Passamos o CSS (EX. Se é pendente, badge-warning)
            'jsonAttributes' => $jsonAttributes,   // Passamos o array pronto para o DetailView
        ]);
    }

    public function actionViewUserPro($id) {
        $model = $this->findModel($id);
        // 1. Processar o Status
        $statusData = $this->getStatusInfo($model->status);

        // 2. Processar os dados do JSON
        $jsonAttributes = $this->getFormattedJsonDataUserPro($model->data);

        return $this->render('view-user-pro', [
            'model' => $model,
            'statusLabel' => $statusData['label'], // Passamos o texto (EX. Pendente)
            'statusClass' => $statusData['class'], // Passamos o CSS (EX. Se é pendente, badge-warning)
            'jsonAttributes' => $jsonAttributes,   // Passamos o array pronto para o DetailView
        ]);
    }

    private function getFormattedJsonDataUserPro($data) {
        $attributes = [];

        $labelsMap = [
            'bio' => 'Biografia',
            'nif' => 'NIF',
            'area_id' => 'Área Principal',
            'website' => 'Website ou Redes Sociais',
            'availability' => 'Disponibilidade Habitual',
            'experience_level' => 'Experiência na Área',
            'professional_name' => 'Nome do Profissional ou Empresa',
        ];

        if (is_array($data) && !empty($data)) {
            foreach ($data as $key => $value) {

                $cleanKey = strtolower($key);

                // Define o Rótulo
                $label = $key;
                foreach ($labelsMap as $mapKey => $mapLabel) {
                    if (strtolower($mapKey) == $cleanKey) {
                        $label = $mapLabel;
                        break;
                    }
                }

                // Define o Valor
                $displayValue = $value;

                switch ($cleanKey) {
                    case 'area_id':
                        switch ($value) {
                            case 1:
                                $displayValue = 'Clínica Veterinária';
                                break;
                            case 2:
                                $displayValue = 'Canil / Abrigo';
                                break;
                            case 3:
                                $displayValue = 'Outro';
                                break;
                            default:
                                $displayValue = 'Desconhecido';
                        }
                        break;

                    case 'experience_level':
                        switch ($value) {
                            case 1:
                                $displayValue = 'Menos de 1 ano';
                                break;
                            case 2:
                                $displayValue = 'Entre 1 a 3 anos';
                                break;
                            case 3:
                                $displayValue = 'Entre 3 a 5 anos';
                                break;
                            case 4:
                                $displayValue = 'Mais de 5 anos';
                                break;
                            default:
                                $displayValue = 'Desconhecido';
                        }
                        break;
                    case 'availability':
                        switch ($value) {
                            case 1:
                                $displayValue = 'Tempo Inteiro (Comercial)';
                                break;
                            case 2:
                                $displayValue = 'Part-time';
                                break;
                            case 3:
                                $displayValue = 'Apenas Fins de Semana';
                                break;
                            case 4:
                                $displayValue = 'Apenas por Marcação';
                                break;
                            default:
                                $displayValue = 'Desconhecido';
                        }
                        break;
                    case 'website':
                        if ($value == null) {
                            $displayValue = 'Desconhecido';
                        } else {
                            $displayValue = Html::encode($value);
                        }
                        break;
                    // --- DEFAULT ---
                    default:
                        $displayValue = Html::encode($value);
                }

                $attributes[] = [
                    'label' => $label,
                    'format' => 'raw',
                    'value' => $displayValue,
                    'contentOptions' => ['class' => 'text-dark font-weight-bold'],
                    'captionOptions' => ['width' => '35%', 'class' => 'text-muted'],
                ];
            }
        } else {
            $attributes[] = [
                'label' => 'Dados',
                'value' => 'Não existem dados adicionais preenchidos.',
                'contentOptions' => ['class' => 'text-muted font-italic'],
            ];
        }
        return $attributes;
    }

    public function getStatusInfo($status)
    {
        switch ($status) {
            case 0: return ['label' => 'Pendente', 'class' => 'badge-warning'];
            case 1: return ['label' => 'Aprovado', 'class' => 'badge-success'];
            case 2: return ['label' => 'Recusado', 'class' => 'badge-danger'];
            default: return ['label' => 'Desconhecido', 'class' => 'badge-secondary'];
        }
    }

    private function getFormattedJsonDataAdoption($data)
    {
        $attributes = [];

        // Mapa de Traduções
        $labelsMap = [
            'age'       => 'Idade',
            'home'      => 'Tipo de Habitação',
            'name'      => 'Nome Completo',
            'bills'     => 'Está ciente dos custos?',
            'motive'    => 'Sobre Candidato',
            'contact'   => 'Contacto',
            'children'  => 'Tem crianças em casa?',
            'FollowUp'  => 'Aceita acompanhamento pós-adoção?',
            'TimeAlone' => 'Tempo que o animal ficará sozinho',
        ];

        if (is_array($data) && !empty($data)) {
            foreach ($data as $key => $value) {

                $cleanKey = strtolower($key);

                // Define o Rótulo
                $label = $key;
                foreach ($labelsMap as $mapKey => $mapLabel) {
                    if (strtolower($mapKey) == $cleanKey) {
                        $label = $mapLabel;
                        break;
                    }
                }

                // Define o Valor
                $displayValue = $value;

                switch ($cleanKey) {
                    case 'home':
                        switch ($value) {
                            case 1:
                                $displayValue = 'Própria';
                                break;
                            case 2:
                                $displayValue = 'Arrendada (Senhorio autoriza)';
                                break;
                            case 3:
                                $displayValue = 'Arrendada (Senhorio não autoriza)';
                                break;
                            default:
                                $displayValue = 'Desconhecido';
                        }
                        break;
                    case 'bills':
                    case 'children':
                    case 'followup':
                        $displayValue = ($value == 1 || $value == '1')
                            ? '<span class="badge badge-success">Sim</span>'
                            : '<span class="badge badge-danger">Não</span>';
                        break;

                    // --- IDADE ---
                    case 'age':
                        $displayValue = $value . ' anos';
                        break;

                    // --- TEMPO SOZINHO ---
                    case 'timealone':
                        switch ($value) {
                            case 0:
                                $displayValue = 'Menos de 4 Horas';
                                break;
                            case 1:
                                $displayValue = 'Entre 4 a 8 Horas';
                                break;
                            case 2:
                                $displayValue = 'Mais de 8 Horas';
                                break;
                            default:
                                $displayValue = 'Desconhecido';
                        }
                        break;

                    // --- DEFAULT ---
                    default:
                        $displayValue = Html::encode($value);
                }

                $attributes[] = [
                    'label' => $label,
                    'format' => 'raw',
                    'value' => $displayValue,
                    'contentOptions' => ['class' => 'text-dark font-weight-bold'],
                    'captionOptions' => ['width' => '35%', 'class' => 'text-muted'],
                ];
            }
        } else {
            $attributes[] = [
                'label' => 'Dados',
                'value' => 'Não existem dados adicionais preenchidos.',
                'contentOptions' => ['class' => 'text-muted font-italic'],
            ];
        }

        return $attributes;
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
