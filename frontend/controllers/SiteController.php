<?php

namespace frontend\controllers;

use Cassandra\Exception\UnauthorizedException;
use common\models\Application;
use frontend\models\ResendVerificationEmailForm;
use frontend\models\VerifyEmailForm;
use Yii;
use yii\base\InvalidArgumentException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;
use common\models\Listing;
use common\models\Animal;
use yii\web\NotFoundHttpException;
use common\models\File;
use yii\web\UploadedFile;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout', 'signup','login'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['login'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
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

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => \yii\web\ErrorAction::class,
            ],
            'captcha' => [
                'class' => \yii\captcha\CaptchaAction::class,
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {

        $model=new File();


        $recentListings = Listing::find()
            ->where(['status' => 1]) // 1 = Aprovado
            ->orderBy(['created_at' => SORT_DESC]) // Mais recentes primeiro
            ->limit(8) // <-- ALTERADO DE 4 PARA 8
            ->with('animal', 'animal.animalType') // Otimização
            ->all();

        // 2. Ir buscar os números para os contadores (hard-coded por agora)

        // 2. VAI BUSCAR O NÚMERO REAL de animais para adoção
        // (Assumindo que status=1 significa "Para Adoção")
        $paraAdocaoCount = Listing::find()
            ->where(['status' => 1])
            ->count();

        // 3. MANTÉM OS ADOTADOS COMO HARD-CODED (como pediu)
        // (Quando a BD estiver pronta, pode mudar isto para:
        //  $adotadosCount = Listing::find()->where(['status' => 2])->count();
        $adotadosCount = 123; // O seu valor hard-coded

        // --- FIM DA ALTERAÇÃO ---

        // 3. Enviar os dados para a view 'index.php'
        return $this->render('index', [
            'recentListings' => $recentListings,
            'paraAdocaoCount' => $paraAdocaoCount,
            'adotadosCount' => $adotadosCount,
            'model' => $model,
        ]);

    }

    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest )  {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            if (\Yii::$app->user->can('loginFrontEnd')) {
                return $this->goBack();
            } else {
//                Yii::$app->user->logout();
//                throw new \yii\web\ForbiddenHttpException('This user doesn\'t have frontend permission.');
//
                Yii::$app->user->logout();
                Yii::$app->session->setFlash('error', 'You are not allowed to access the frontend.');
                return $this->redirect(['site/login']);
            }
            //return $this->goBack();
        }

        $model->password = '';

        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return mixed
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail(Yii::$app->params['adminEmail'])) {
                Yii::$app->session->setFlash('success', 'Thank you for contacting us. We will respond to you as soon as possible.');
            } else {
                Yii::$app->session->setFlash('error', 'There was an error sending your message.');
            }

            return $this->refresh();
        }

        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return mixed
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    /**
     * Signs user up.
     *
     * @return mixed
     */
    public function actionSignup()
    {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post()) && $model->signup()) {
            Yii::$app->session->setFlash('success', 'Thank you for registration. Please check your inbox for verification email.');
            return $this->goHome();
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');

                return $this->goHome();
            }

            Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for the provided email address.');
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'New password saved.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }

    /**
     * Verify email address
     *
     * @param string $token
     * @throws BadRequestHttpException
     * @return yii\web\Response
     */
    public function actionVerifyEmail($token)
    {
        try {
            $model = new VerifyEmailForm($token);
        } catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
        if ($model->verifyEmail()) {
            Yii::$app->session->setFlash('success', 'Your email has been confirmed!');
            return $this->goHome();
        }

        Yii::$app->session->setFlash('error', 'Sorry, we are unable to verify your account with provided token.');
        return $this->goHome();
    }

    /**
     * Resend verification email
     *
     * @return mixed
     */
    public function actionResendVerificationEmail()
    {
        $model = new ResendVerificationEmailForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');
                return $this->goHome();
            }
            Yii::$app->session->setFlash('error', 'Sorry, we are unable to resend verification email for the provided email address.');
        }

        return $this->render('resendVerificationEmail', [
            'model' => $model
        ]);
    }

    public function actionDetail($id)
    {
        // 1. O Controller PROCURA o animal na Base de Dados
        // Usamos ->with() para otimizar e ir buscar as relações (raça, tipo)
        $model = Animal::find()
            ->where(['id' => $id])
            ->with('animalType', 'breed') // Carrega as tabelas relacionadas
            ->one();

        // 2. Verifica se o animal existe
        if ($model === null) {
            throw new NotFoundHttpException('O animal que procura não existe.');
        }

        // 3. O Controller ENVIA o $model para a View
        return $this->render('detail', [
            'model' => $model, // <-- AQUI ESTÁ A VARIÁVEL QUE FALTAVA
        ]);
    }

    public function actionAnimal()
    {
       /* return $this->render('animal'); */

        $listings = Listing::find()
            ->where(['status' => 1]) // Assumindo que '1' = Anúncio Aprovado
            ->with('animal', 'animal.animalType') // Otimização: Carrega os animais e tipos de uma só vez
            ->orderBy(['created_at' => SORT_DESC]) // Mostrar os mais recentes primeiro
            ->all(); // Pede todos os resultados como um array

        // 2. Enviamos o array de $listings para a view
        return $this->render('animal', [
            'listings' => $listings,
        ]);
    }

    public function actionUpload()
    {
        $model = new File();

        if (Yii::$app->request->isPost) {
            $model->imageFile = UploadedFile::getInstances($model, 'imageFile');
            if ($model->upload()) {
                // file is uploaded successfully
                return $this->goHome();
            } else {
                dd('error');
                return "error";
            }
        }

        return $this->render('upload', ['model' => $model]);
    }


    public function actionCreateListing()
    {
        // 1. CRIAMOS O MODELO VAZIO
        $model = new Animal();

        if ($this->request->isPost) {

            // 2. Carregar os dados do formulário (name, age, etc.)
            $model->load(Yii::$app->request->post());

            // 3. Apanhar as instâncias dos ficheiros
            $model->imageFiles = UploadedFile::getInstances($model, 'imageFiles');

            // 4. Validar o modelo (incluindo as regras dos 'imageFiles')
            if ($model->validate()) {

                // 5. Iniciar uma Transação
                $transaction = Yii::$app->db->beginTransaction();
                try {

                    // 6. Definir o dono e guardar o ANIMAL
                    $model->user_id = Yii::$app->user->id;
                    if (!$model->save(false)) { // 'false' para não validar outra vez
                        throw new \Exception('Falha ao guardar o animal.');
                    }

                    // 7. Guardar os Ficheiros (agora que temos o $model->id)
                    foreach ($model->imageFiles as $file) {

                        // 7a. Gerar caminhos (usando os 'aliases')
                        $userId = $model->user_id;
                        $animalId = $model->id;
                        $basePath = Yii::getAlias('@storagePath') . "/users/{$userId}/animals/{$animalId}";
                        $baseUrl = Yii::getAlias('@storageUrl') . "/users/{$userId}/animals/{$animalId}";
                        \yii\helpers\FileHelper::createDirectory($basePath);

                        // 7b. Guardar o ficheiro no disco
                        $fileName = Yii::$app->security->generateRandomString() . '.' . $file->extension;
                        $path = $basePath . '/' . $fileName;
                        if (!$file->saveAs($path)) {
                            throw new \Exception('Falha ao guardar o ficheiro no disco.');
                        }


                        // 7c. Guardar o registo na tabela 'file'
                        $fileModel = new File();
                        $fileModel->user_id = $userId;
                        $fileModel->animal_id = $animalId;
                        $fileModel->type_id = 1;
                        $fileModel->path = $baseUrl . '/' . $fileName; // O URL público

                        if (!$fileModel->save()) {
                            throw new \Exception('Falha ao guardar o caminho do ficheiro na BD.');
                        }

                    }

                    // 8. (Opcional) Criar o Listing (Anúncio)
                    $listingModel = new Listing();
                    $listingModel->animal_id = $model->id;
                    $listingModel->user_id = Yii::$app->user->id;
                    $listingModel->status = 1; // 0 = Pendente de Aprovação
                    if (!$listingModel->save()) {
                        throw new \Exception('Falha ao criar o anúncio (listing).');
                    }

                    // 9. Se tudo correu bem, 'cometer' a transação
                    $transaction->commit();
                    Yii::$app->session->setFlash('success', 'Anúncio criado com sucesso!');
                    return $this->redirect(['detail', 'id' => $model->id]);

                } catch (\Exception $e) {
                    // 10. Se algo falhou, fazer 'rollback' (desfazer tudo)
                    $transaction->rollBack();
                    Yii::$app->session->setFlash('error', 'Ocorreu um erro: ' . $e->getMessage());
                }
            } else {
                // Erro de validação (ex: nome em branco ou foto em falta)
                Yii::$app->session->setFlash('error', 'Por favor, corrija os erros no formulário.');
            }
        }

        // 11. (QUANDO A PÁGINA É CARREGADA PELA 1ª VEZ)
        // Enviar o $model (vazio) para a view
        return $this->render('create-listing', [
            'model' => $model,
        ]);
    }

    public function actionApply($animal_id) {

        $model = new Application();

        // 1. Ir buscar o animal para mostrar o nome, etc.
        $animal = \common\models\Animal::findOne($animal_id);
        if ($animal === null) {
            throw new \yii\web\NotFoundHttpException('Animal não encontrado.');
        }

        // 2. A action renderiza a view 'apply.php'
        return $this->render('apply', [
            'animal' => $animal,
        ]);
    }


}
