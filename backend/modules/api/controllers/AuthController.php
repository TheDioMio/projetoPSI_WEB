<?php

namespace backend\modules\api\controllers;

use Yii;
use yii\filters\auth\HttpBasicAuth;
use yii\rest\Controller;
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
                if ($user && $user->status == User::STATUS_ACTIVE && $user->validatePassword($password)) {
                    return $user;
                }
                return null;
            },
        ];

        return $behaviors;
    }

    public function verbs()
    {
        return [
            'login' => ['POST'],
        ];
    }

    public function actionLogin()
    {
        /** @var User|null $user */
        $user = Yii::$app->user->identity;

        if (!$user) {
            throw new UnauthorizedHttpException('Invalid credentials.');
        }

        return [
            'token' => $user->auth_key,
            'user_id' => $user->id,
            'username' => $user->username,
        ];
    }

    public function actionLogin2()
    {
        $request = Yii::$app->request;
        $username = $request->post('username');
        $password = $request->post('password');

        /** @var User $user */
        $user = User::findByUsername($username);

        //colocar a devolver a mensagem correta 200 ok
        if (!$user ){
            return [
                'status' => 404,
                'success' => false,
                'message' => 'User not found',
            ];
        }

        if(!$user->validatePassword($password)) {
            return [
                'success' => false,
                'message' => 'Invalid password'
            ];
        }

        //ir buscar o token a bd
        $token =

        //
//        // gerar token simples
//        $token = base64_encode($user->id . '-' . time());
//
//        // gravar token na BD
//        $user->auth_token = $token;
//        $user->save(false);

        return [
            'success' => true,
            'token' => $token
        ];
    }

}
