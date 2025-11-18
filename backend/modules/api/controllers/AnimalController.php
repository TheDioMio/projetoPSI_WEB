<?php

namespace backend\modules\api\controllers;

use yii\filters\auth\QueryParamAuth;
use yii\rest\ActiveController;

/**
 * Default controller for the `api` module
 */
class AnimalController extends ActiveController
{
    public $modelClass = 'common\models\Animal';


    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => QueryParamAuth::className(),
            // 'exept' => ['index', 'view'],
        ];
        return $behaviors;
    }

}
