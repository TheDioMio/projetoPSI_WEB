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



    /**
     * GET /api/users/me
     */
    public function actionMe()
    {
        //futuramente colocar a devolver mais informação
        $user = Yii::$app->user->identity;

//        if (!$user) {
//            throw new UnauthorizedHttpException('Invalid credentials.');
//        }

        // if $user == null ou algo de genero tem de gerar um erro para devolver
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
            'avatar' =>$user->profileImage->path,
        ];
    }

//    public static function findIdentityByAccessToken($token, $type = null)
//    {
//        return static::findOne(['auth_key' => $token, 'status' => self::STATUS_ACTIVE]);
//    }

}
