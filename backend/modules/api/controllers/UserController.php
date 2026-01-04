<?php

namespace backend\modules\api\controllers;

use common\models\User;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBasicAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\auth\QueryParamAuth;
use yii\rest\ActiveController;
USE yii;
use yii\web\UnauthorizedHttpException;

/**
 * Default controller for the `api` module
 */
class UserController extends ActiveController
{
    public $modelClass = 'backend\modules\api\models\User';

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::class,
        ];

        return $behaviors;
    }


    public function actions()
    {
        $actions = parent::actions();

        unset($actions['update']);
        unset($actions['view']);
        unset($actions['create']);
        unset($actions['delete']);
        unset($actions['index']);

        return $actions;
    }


    /**
     * PUT /api/users/me
     */
    public function actionUpdateMe()
    {
        $user = Yii::$app->user->identity;

        if (!$user) {
            throw new UnauthorizedHttpException('Invalid credentials.');
        }

        $body = Yii::$app->request->bodyParams;

        // Atualizar apenas campos permitidos
        $user->name     = $body['name']     ?? $user->name;
        $user->username = $body['username'] ?? $user->username;
        $user->email    = $body['email']    ?? $user->email;
        $user->address  = $body['address']  ?? $user->address;

        if (!$user->validate()) {
            Yii::$app->response->statusCode = 422;
            return [
                'success' => false,
                'errors' => $user->errors
            ];
        }

        if (!$user->save(false)) {
            Yii::$app->response->statusCode = 500;
            return [
                'success' => false,
                'message' => 'Erro ao atualizar perfil'
            ];
        }

        return [
            'success' => true,
            'id' => $user->id,
            'username' => $user->username,
            'name' => $user->name,
            'email' => $user->email,
            'address' => $user->address,
            'avatar' => $user->profileImage->path ?? null,
        ];
    }

    /**
     * GET /api/users/me
     */
    public function actionMe()
    {
        //futuramente colocar a devolver mais informação
        $user = Yii::$app->user->identity;

        if (!$user) {
            throw new UnauthorizedHttpException('Invalid credentials.');
        }


        // se não estiver ativo também não pode deixar receber os dados
        //etc validações
        // retornar o statusCode


        return [
            'success' => true,
            'id' => $user->id,
            'username' => $user->username,
            'name' => $user->name,
            'email' => $user->email,
            'address' => $user->address,
            'avatar' => $user->profileImage ? $user->profileImage->path : null,
        ];
    }

//    public static function findIdentityByAccessToken($token, $type = null)
//    {
//        return static::findOne(['auth_key' => $token, 'status' => self::STATUS_ACTIVE]);
//    }

}
