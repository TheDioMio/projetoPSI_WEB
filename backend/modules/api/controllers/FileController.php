<?php

namespace backend\modules\api\controllers;

use backend\modules\api\models\File;
use common\models\Animal;
use Yii;
use yii\filters\auth\HttpBearerAuth;
use yii\helpers\FileHelper;
use yii\web\BadRequestHttpException;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\UploadedFile;

class FileController extends \yii\rest\Controller
{
    public $modelClass = 'backend\modules\api\models\File';

    /**
     * Ativa autenticação por Bearer Token
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::className(),
            // 'exept' => ['index', 'view'],
        ];
        return $behaviors;
    }




    /**
     * UPDATE / CREATE avatar do utilizador autenticado
     *
     * Endpoint:
     * POST /api/files/avatar
     *
     * Body (multipart/form-data):
     * - file : imagem (jpg/png)
     */
    public function actionUpdateAvatar()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $user = Yii::$app->user->identity;

        if (!$user) {
            throw new ForbiddenHttpException('Not authenticated');
        }

        /**
         * (RBAC)
         * Permissão para alterar avatar
         */
//        if (!Yii::$app->user->can('uploadAvatar')) {
//            throw new ForbiddenHttpException('Permission denied');
//        }

        /**
         * Receber ficheiro
         */
        $uploadedFile = UploadedFile::getInstanceByName('file');

        if (!$uploadedFile) {
            throw new BadRequestHttpException('No file received');
        }

        /**
         * Validações básicas
         */
        $allowedTypes = ['image/jpeg', 'image/png'];
        if (!in_array($uploadedFile->type, $allowedTypes)) {
            throw new BadRequestHttpException('Invalid file type');
        }

        /**
         * Apagar avatar antigo (se existir)
         */
        $oldAvatar = File::find()
            ->where([
                'user_id' => $user->id,
                'type_id' => 2, // avatar
            ])
            ->one();

        if ($oldAvatar) {
            $oldPath = Yii::getAlias('@frontend/web/' . $oldAvatar->path);
            if (file_exists($oldPath)) {
                @unlink($oldPath);
            }
            $oldAvatar->delete();
        }

        /**
         * Criar diretório
         */
        $basePath = Yii::getAlias("@frontend/web/uploads/users/");
        FileHelper::createDirectory($basePath);

        /**
         * Guardar ficheiro
         */
        $fileName = uniqid() . '.' . $uploadedFile->extension;
        $uploadedFile->saveAs("$basePath/$fileName");

        /**
         * Guardar registo na BD
         */
        $file = new File();
        $file->type_id = 2; // avatar
        $file->user_id = $user->id;
        $file->path = "/uploads/users/{$fileName}";

        if (!$file->save()) {
            throw new BadRequestHttpException($file->errors);
        }

        /**
         * Resposta
         */
        Yii::$app->response->statusCode = 200;

        return [
            'success' => true,
            'id' => $file->id,
            'path' => $file->path,
            'message' => 'Avatar updated successfully'
        ];
    }


//    /**
//     * 🗑 DELETE FOTO DO ANIMAL
//     * DELETE /api/files/{id}
//     */
//    public function actionDelete($id)
//    {
//        $file = File::findOne($id);
//
//        // 🔍 ficheiro existe?
//        if (!$file || $file->type_id !== 1) {
//            throw new NotFoundHttpException('Image not found');
//        }
//
//        // 🔍 animal existe?
//        $animal = Animal::findOne($file->animal_id);
//        if (!$animal) {
//            throw new NotFoundHttpException('Animal not found');
//        }
//
//        // 🔐 só o dono do animal
//        if ($animal->user_id !== Yii::$app->user->id) {
//            throw new ForbiddenHttpException('You cannot delete this image');
//        }
//
//        // ❌ não apagar a última foto
//        $photoCount = File::find()
//            ->where([
//                'animal_id' => $animal->id,
//                'type_id' => 1
//            ])
//            ->count();
//
//        if ($photoCount <= 1) {
//            throw new ForbiddenHttpException('An animal must have at least one photo');
//        }
//
//        // 🧹 apagar ficheiro físico
//        $physicalPath = Yii::getAlias('@frontend/web/' . $file->path);
//        if (file_exists($physicalPath)) {
//            unlink($physicalPath);
//        }
//
//        // 🗑 apagar registo BD
//        $file->delete();
//
//        // ✅ sucesso
//        Yii::$app->response->statusCode = 204;
//        //ponderar colocar a devolver "success" =>true
//        return null;
//    }

    public function actionCreate()
    {
        Yii::error($_POST, 'UPLOAD_DEBUG');
        Yii::error(array_keys($_FILES), 'UPLOAD_DEBUG');
        Yii::error('ACTION FILE CREATE HIT', 'UPLOAD_DEBUG');

        Yii::$app->response->format = Response::FORMAT_JSON;

        $animalId = Yii::$app->request->post('animal_id');

        // tenta buscar por "files"
        $files = UploadedFile::getInstancesByName('files');

        if (empty($files)) {
            $files = [];
            foreach ($_FILES as $key => $fileInfo) {
                if (strpos($key, 'files') === 0) {
                    $uploaded = UploadedFile::getInstanceByName($key);
                    if ($uploaded !== null) {
                        $files[] = $uploaded;
                    }
                }
            }
        }

        if (!$animalId || empty($files)) {
            throw new BadRequestHttpException('animal_id and files[] are required');
        }

        $animal = Animal::findOne($animalId);
        if (!$animal) {
            throw new NotFoundHttpException('Animal not found');
        }

        if ($animal->user_id !== Yii::$app->user->id) {
            throw new ForbiddenHttpException('You cannot upload photos to this animal');
        }

        $uploadPath = Yii::getAlias('@frontend/web/uploads/animals/' . $animalId);
        FileHelper::createDirectory($uploadPath);

        $transaction = Yii::$app->db->beginTransaction();
        $saved = [];

        try {
            foreach ($files as $file) {

                $filename = uniqid('animal_') . '.' . $file->extension;
                $fullPath = $uploadPath . '/' . $filename;

                if (!$file->saveAs($fullPath)) {
                    throw new \Exception('Failed to save image');
                }

                $model = new File();
                $model->type_id = 1;
                $model->animal_id = $animalId;
                $model->user_id = Yii::$app->user->id;
                $model->path = '/uploads/animals/' . $animalId . '/' . $filename;
                $model->created_at = date('Y-m-d H:i:s');

                if (!$model->save()) {
                    @unlink($fullPath);
                    throw new \Exception('Failed to save DB record');
                }

                $saved[] = $model;
            }

            $transaction->commit();

        } catch (\Throwable $e) {

            $transaction->rollBack();

            foreach ($saved as $img) {
                $p = Yii::getAlias('@frontend/web/' . $img->path);
                if (file_exists($p)) {
                    @unlink($p);
                }
                $img->delete();
            }

            throw new BadRequestHttpException($e->getMessage());
        }

        Yii::$app->response->statusCode = 201;
        return [
            'success' => true,
            'uploaded' => count($saved),
            'files' => $saved,
        ];
    }


    public function actionDelete()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $userId = Yii::$app->user->id;
        $ids = Yii::$app->request->bodyParams['photo_ids'] ?? [];

        if (empty($ids)) {
            throw new BadRequestHttpException('No photo_ids provided');
        }

        $files = File::find()->where(['id' => $ids])->all();

        foreach ($files as $file) {

            if ($file->user_id !== $userId) {
                throw new ForbiddenHttpException('Não autorizado');
            }

            $path = Yii::getAlias('@frontend/web/' . $file->path);

            if (file_exists($path)) {
                unlink($path);
            }

            $file->delete();
        }

        return [
            'success' => true,
            'deleted' => count($files)
        ];
    }




    /**
     * 👤 VER AVATAR DE UM USER
     * GET /api/files/avatar/{user_id}
     */
    public function actionViewAvatar($user_id)
    {
        $file = File::find()
            ->where([
                'user_id' => $user_id,
                'type_id' => 2 //avatar =2
            ])
            ->orderBy(['created_at' => SORT_DESC])
            ->one();

        if (!$file) {
            throw new NotFoundHttpException('Avatar not found');
        }

        Yii::$app->response->statusCode = 200;
        return $file;
    }


    /**
     * 📂 LISTAR TODAS AS FOTOS DE UM ANIMAL
     * GET /api/files/animal/{animal_id}
     */
    public function actionViewAnimal($animal_id)
    {
        $animal = Animal::findOne($animal_id);

        if (!$animal) {
            throw new NotFoundHttpException('Animal not found');
        }

        $files = File::find()
            ->where([
                'animal_id' => $animal_id,
                'type_id' => 1 // animal_photo=1
            ])
            ->orderBy(['created_at' => SORT_ASC])
            ->all();

        Yii::$app->response->statusCode = 200;
        return [
            'animal_id' => $animal_id,
            'count' => count($files),
            'files' => $files
        ];
    }





}
