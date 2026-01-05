<?php

namespace frontend\controllers;

class PartnersController extends \yii\web\Controller
{
    public function actionShelters()
    {
        return $this->render('shelters');
    }

    public function actionVets()
    {
        return $this->render('vets');
    }

}
