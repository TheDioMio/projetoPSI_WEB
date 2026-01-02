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

        // Login por Basic Auth
        $behaviors['authenticator'] = [
            'class' => HttpBasicAuth::class,
            'auth' => function ($username, $password) {
                $user = User::findByUsername($username);
                if (!$user || !$user->validatePassword($password)) {
                    // 401
                    throw new UnauthorizedHttpException('Invalid credentials.');
                }

                if ($user->status !== User::STATUS_ACTIVE) {
                    // 403
                    throw new ForbiddenHttpException('User inactive.');
                }
                return $user;
            },
        ];

        return $behaviors;
    }

    public function verbs()
    {
        return [
            'login' => ['POST', 'OPTIONS'],
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
