<?php

namespace frontend\controllers;

class ProfileController extends \yii\web\Controller
{

    public function actionProfile() {

        return $this->render('profile');
    }

}