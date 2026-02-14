<?php

namespace backend\modules\api\controllers;

use Yii;
use yii\filters\auth\HttpBasicAuth;
use yii\rest\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\UnauthorizedHttpException;
use common\models\User;

class AuthController extends Controller
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['authenticator'] = [
            'class' => HttpBasicAuth::class,
            'only' => ['login'], // 🔥 só login usa Basic Auth
            'auth' => function ($username, $password) {
                $user = User::findByUsername($username);

                if (!$user || !$user->validatePassword($password)) {
                    throw new UnauthorizedHttpException('Invalid credentials.');
                }

                if ($user->status !== User::STATUS_ACTIVE) {
                    throw new ForbiddenHttpException('User inactive.');
                }

                return $user;
            },
        ];


        // Login por Basic Auth  ANTES DE IMPLEMENTAR O METODO SIGNUP
//        $behaviors['authenticator'] = [
//            'class' => HttpBasicAuth::class,
//            'auth' => function ($username, $password) {
//                $user = User::findByUsername($username);
//                if (!$user || !$user->validatePassword($password)) {
//                    // 401
//                    throw new UnauthorizedHttpException('Invalid credentials.');
//                }
//
//                if ($user->status !== User::STATUS_ACTIVE) {
//                    // 403
//                    throw new ForbiddenHttpException('User inactive.');
//                }
//                return $user;
//            },
//        ];

        return $behaviors;
    }

    public function verbs()
    {
        return [
            'login' => ['POST', 'OPTIONS'],
            'signup' => ['POST', 'OPTIONS'],
        ];
    }

    public function actions()
    {
        return [
            'options' => [
                'class' => 'yii\rest\OptionsAction',
            ],
        ];
    }


    public function actionSignup()
    {
        $request = Yii::$app->request;
        $data = $request->getBodyParams();

        if (empty($data['username']) || empty($data['email']) || empty($data['password'])) {
            Yii::$app->response->statusCode = 400;
            return ['error' => 'Missing required fields'];
        }

        // verificar se já existe
        if (User::find()->where(['username' => $data['username']])->exists()) {
            Yii::$app->response->statusCode = 409;
            return ['error' => 'Username already exists'];
        }

        if (User::find()->where(['email' => $data['email']])->exists()) {
            Yii::$app->response->statusCode = 409;
            return ['error' => 'Email already exists'];
        }

        $user = new User();
        $user->scenario = 'create';

        // regras de negócio da API
        $user->username = $data['username'];
        $user->name = $data['username'];
        $user->email = $data['email'];
        $user->address = "Sem endereço";
        $user->role_id = User::ROLE_USER;             
        $user->status = User::STATUS_ACTIVE;
        $user->password = $data['password'];
        $user->setPassword($data['password']);
        $user->generateAuthKey();
        $user->generateEmailVerificationToken();

        if (!$user->save()) {
            Yii::$app->response->statusCode = 422;
            return [
                'error' => 'Validation failed',
                'details' => $user->errors
            ];
        }

        // atribuir RBAC role "user"
        $auth = Yii::$app->authManager;
        $role = $auth->getRole('user');
        if ($role) {
            $auth->assign($role, $user->id);
        }

        // criar avatar default
        $file = new \common\models\File();
        $file->user_id = $user->id;
        $file->path = 'img/user_default_avatar.jpg';
        $file->type_id = 2;
        $file->created_at = date('Y-m-d H:i:s');
        $file->save(false);

        Yii::$app->response->statusCode = 201;

        return [
            'success' => true,
            'id' => $user->id,
            'username' => $user->username,
            'email' => $user->email,
            'token' => $user->auth_key
        ];
    }





    public function actionLogin()
    {
        // Responde OPTIONS automaticamente
        if (Yii::$app->request->isOptions) {
            Yii::$app->response->statusCode = 200;
            return [];
        }

        // resto do teu código...
        Yii::error('METHOD=' . Yii::$app->request->method, 'AUTH_DEBUG');
        // ...
        Yii::error('METHOD=' . Yii::$app->request->method, 'AUTH_DEBUG');
        Yii::error('HEADERS=' . json_encode(Yii::$app->request->headers->toArray()), 'AUTH_DEBUG');
        // o basicAuth já validou tudo

        /** @var User|null $user */
        $user = Yii::$app->user->identity;


        if (!$user) {
            Yii::$app->response->statusCode = 403;
            return ['error' => 'User identity not set'];
        }


        // devolve o code 200 de OK
        Yii::$app->response->statusCode = 200;
        return [
            'success' => true,
            'token' => $user->auth_key,
            'id' => $user->id,
        ];
    }
}
