<?php

namespace frontend\controllers;

use common\models\Application;
use frontend\models\ApplicationSearch;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use common\models\Animal;
use Yii;
use yii\base\DynamicModel;
use yii\web\UploadedFile;


/**
 * ApplicationController implements the CRUD actions for Application model.
 */
class ApplicationController extends Controller
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
                    'except' => ['error'],
                    'denyCallback' => function () {
                        if (Yii::$app->user->can('loginFrontend')) {
                            return Yii::$app->response->redirect(['/site/index']);
                        }
                        return Yii::$app->response->redirect(['/site/login']);
                    },
                    'rules' => [
                        [
                            'allow' => true,
                            'roles' => ['loginFrontend', 'applicationManeger'],
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

    public function actionInbox()
    {
        $searchModel = new ApplicationSearch();
        $dataProvider = $searchModel->searchInbox(Yii::$app->request->queryParams);

        return $this->render('inbox', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionOutbox()
    {
        $searchModel = new ApplicationSearch();
        $dataProvider = $searchModel->searchOutbox(Yii::$app->request->queryParams);

        return $this->render('outbox', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }




    /**
     * Lists all Application models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new ApplicationSearch();
        $params = $this->request->queryParams;
        $params['ApplicationSearch']['target_user_id'] = Yii::$app->user->id;
        $dataProvider = $searchModel->search($params);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Application model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id, $box = 'inbox')
    {
        $model = $this->findModel($id);

        if ($box === 'inbox' && !$model->isRead) {
            $model->markAsRead();
        }

        return $this->render('view', [
            'model' => $this->findModel($id),
            'box' => $box,
        ]);
    }

    public function actionApprove($id)
    {
        $model = $this->findModel($id);

        if ($model->approve()) {
            Yii::$app->session->setFlash('success', 'Candidatura aprovada.');
        } else {
            Yii::$app->session->setFlash('error', 'Não foi possível aprovar a candidatura.');
        }

        return $this->redirect(['inbox']);
    }


    public function actionReject($id)
    {
        $model = $this->findModel($id);

        if ($model->reject()) {
            Yii::$app->session->setFlash('success', 'Candidatura rejeitada.');
        } else {
            Yii::$app->session->setFlash('error', 'Não foi possível rejeitar a candidatura.');
        }

        return $this->redirect(['inbox']);
    }

    public function actionCancel($id)
    {
        $model = $this->findModel($id);

        if ($model->cancel()) {
            Yii::$app->session->setFlash('success', 'Candidatura cancelada.');
        } else {
            Yii::$app->session->setFlash('error', 'Não foi possível cancelar a candidatura.');
        }

        return $this->redirect(['outbox']);
    }



    /**
     * Creates a new Application model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Application();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Application model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
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

    /**
     * Deletes an existing Application model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Application model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Application the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Application::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionApply($animal_id)
    {
        //Tem de estar autenticado para candidatar
        if (Yii::$app->user->isGuest) {
            Yii::$app->session->setFlash('error', 'Faça login para se candidatar!');
            return $this->redirect(['site/login']);
        }

        $animal = Animal::findOne($animal_id);
        if ($animal === null) {
            throw new NotFoundHttpException('Animal não encontrado.');
        }

        $model = new Application();
        $model->scenario = Application::SCENARIO_ADOPTION;
        $model->animal_id = $animal_id;
        $model->user_id = Yii::$app->user->id;
        $model->target_user_id = Yii::$app->user->id;

        $model->status = Application::STATUS_SENT;
        $model->created_at = date('Y-m-d H:i:s');

        $model->type = Application::TYPE_ADOPTION;



        // Trata o POST
        if ($model->load(Yii::$app->request->post())) {
            //Garantir que data é array; o beforeSave fará json_encode
            if (!is_array($model->data)) {
                $model->data = (array)$model->data;
            }
            //guardar o nome da candidatura na descrição
            if (isset($model->data['name'])) {
                $model->description = $model->data['name'];
            }

            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Candidatura enviada com sucesso!');
                return $this->redirect(['listings/detail', 'id' => $animal->id]);
            } else {
                Yii::error(['apply_save_errors' => $model->errors], __METHOD__);
                Yii::$app->session->setFlash('error', 'Corrige os erros do formulário.');
            }
        }

        // GET inicial ou POST inválido → volta a mostrar o form
        return $this->render('apply', [
            'animal' => $animal,
            'model'  => $model,
        ]);
    }
    public function actionMyApplications()
    {
        return $this->render('my-applications');
    }


    public function actionApplyUserPro()
    {
        //1.º Autenticação:
        if (Yii::$app->user->isGuest) {
            Yii::$app->session->setFlash('error', 'Faça login para se candidatar!');
            return $this->redirect(['site/login']);
        }

        //2.º Criar o modelo dinâmico para o formulário, isto deixa-nos validar e ter muito mais controlo sobre o que vai para a 'data'.
        $formModel = new DynamicModel([
            'professional_name', 'nif', 'area_id', 'experience_level',
            'website', 'availability', 'bio'
        ]);

        //3.º Definir regras de validação para o formulário
        $formModel->addRule(['professional_name', 'bio', 'availability'], 'string')
            ->addRule(['professional_name', 'nif', 'area_id', 'experience_level', 'bio'], 'required')
            ->addRule(['nif', 'area_id', 'experience_level'], 'integer')
            ->addRule(['website'], 'url', ['defaultScheme' => 'http']);


        //4.º Processar o POST
        if ($formModel->load(Yii::$app->request->post())) {
            //Validação se o formulário é válido
            if ($formModel->validate()) {
                //A. Preparar os dados para guardar na nossa BD.
                $application = new Application();

                $application->scenario = Application::SCENARIO_USER_PRO; //NUNCA ESQUECER DE DECLARAR QUAL É O CENÁRIO!!!!!
                $application->user_id = Yii::$app->user->id;
                $application->type = Application::TYPE_USER_PRO; //Declara logo que a candidatura é de tipo 2 (userPro), ISTO ESTÁ TUDO DEFINIDO EM CIMA, CONSTANTES!
                $application->status = Application::STATUS_SENT; //Está pendente, ainda não foi vista sequer.
                $application->created_at = date('Y-m-d H:i:s');

                $application->animal_id = 16; //FORÇA A ENVIAR UM ANIMAL_ID, já que na Application é obrigatório um animal_id. Só para testes.

                //Professional name é o mesmo que o nosso 'name', esqueci-me que tínhamos essa coluna na BD, mas está a funcionar por isso, por agora, não se mexe
                $application->description = $formModel->professional_name;

                //Empacotar tudo no JSON para a 'data'
                $dataToSave = $formModel->getAttributes();
                $application->data = $dataToSave;

                //B. Guardar
                if ($application->save()) {
                    //Isto é tipo o Toast de Android
                    Yii::$app->session->setFlash('success', 'Candidatura submetida com sucesso! Vamos analisar os seus dados.');
                    return $this->redirect(['site/index']);
                } else {
                    Yii::$app->session->setFlash('error', 'Erro ao guardar a candidatura na base de dados.');
                }
            } else {
                Yii::$app->session->setFlash('error', 'Corrija, por favor, os erros no formulário.');
            }
        }

        //5.º Renderizar a View
        return $this->render('apply-user-pro', [
            'model' => $formModel,  // Enviamos o DynamicModel para a view desenhar os campos
        ]);
    }


}
