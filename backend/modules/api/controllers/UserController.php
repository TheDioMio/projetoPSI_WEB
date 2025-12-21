<?php

namespace backend\modules\api\controllers;

use common\models\User;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBasicAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\auth\QueryParamAuth;
use yii\rest\ActiveController;
USE yii;

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
