<?php

namespace frontend\controllers;

use Yii;

class ProfileController extends \yii\web\Controller
{

    public function actionProfile()
    {
        $user = Yii::$app->user->identity;

        return $this->render('profile', [
            'user' => $user,
        ]);
    }

}