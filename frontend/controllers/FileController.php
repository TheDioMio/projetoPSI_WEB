<?php

namespace frontend\controllers;

use common\models\File;
use Yii;
use yii\web\NotFoundHttpException;

class FileController extends \yii\web\Controller
{
    public function actionDelete($id)
    {
        $file = File::findOne($id);

        if (!$file) {
            throw new NotFoundHttpException();
        }

        $animal = $file->animal;
        $numPhotos = count($animal->files);

        // Impede apagar se for a última
        if ($numPhotos <= 1) {
            Yii::$app->session->setFlash('error', 'Não pode remover a última fotografia.');
            return $this->redirect(Yii::$app->request->referrer);
        }

        // ⚠️ Apagar ficheiro físico do disco
        $absolutePath = Yii::getAlias('@webroot') . $file->path;
        if (file_exists($absolutePath)) {
            unlink($absolutePath);
        }

        // ⚠️ Apagar o registo da BD
        $file->delete();

        Yii::$app->session->setFlash('success', 'Fotografia removida.');

        // Volta para a página anterior
        return $this->redirect(Yii::$app->request->referrer);
    }


//    public function actionDelete($id)
//    {
//        $file = File::findOne($id);
//
//        if (!$file) {
//            throw new NotFoundHttpException();
//        }
//
//        $animal = $file->animal;
//        $numPhotos = count($animal->files);
//
//        // Impede apagar se for a última
//        if ($numPhotos <= 1) {
//            Yii::$app->session->setFlash('error', 'Não pode remover a última fotografia.');
//            return $this->redirect(Yii::$app->request->referrer);
//        }
//
//        $file->delete();
//        Yii::$app->session->setFlash('success', 'Fotografia removida.');
//
//        return $this->redirect(Yii::$app->request->referrer);
//    }

}
