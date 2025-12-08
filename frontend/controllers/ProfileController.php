<?php

namespace frontend\controllers;

use common\models\File;
use Yii;
use yii\web\UploadedFile;

class ProfileController extends \yii\web\Controller
{

    public function actionProfile()
    {
        $user = Yii::$app->user->identity;

        return $this->render('profile', [
            'user' => $user,
        ]);
    }

    public function actionUploadImage()
    {
        $user = Yii::$app->user->identity;

        $user->imageFile = UploadedFile::getInstance($user, 'imageFile');

        if ($user->imageFile) {

            // Apagar imagem antiga
            File::deleteAll(['user_id' => $user->id, 'type_id' => 2]);

            $relative = 'uploads/users/' . uniqid() . '.' . $user->imageFile->extension;
            $absolute = Yii::getAlias('@webroot') . '/' . $relative;

            $user->imageFile->saveAs($absolute);

            // Criar novo registo na tabela file
            $file = new File();
            $file->user_id = $user->id;
            $file->type_id = 2;
            $file->path = '/' . $relative;
            $file->created_at = date('Y-m-d H:i:s');
            $file->save(false);

            Yii::$app->session->setFlash('success', 'Imagem atualizada com sucesso.');
        }

        return $this->redirect(['profile']);
    }

}