<?php

namespace frontend\controllers;

use yii\web\Controller;
use yii\web\Response;
use yii\helpers\ArrayHelper;
use common\models\Breed;

class BreedController extends \yii\web\Controller
{

    public function actionGetByType($id)
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;
        return \yii\helpers\ArrayHelper::map(
            \common\models\Breed::find()
                ->where(['animal_type_id' => $id])
                ->orderBy(['description' => SORT_ASC])
                ->all(),
            'id',
            'description'
        );
        //return ['status' => 'ok', 'id' => $id];
    }




}
