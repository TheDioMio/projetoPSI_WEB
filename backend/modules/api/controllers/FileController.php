<?php

namespace backend\modules\api\controllers;

use backend\modules\api\models\File;
use common\models\Animal;
use Yii;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\VerbFilter;
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
        ];

        $behaviors['verbs'] = [
            'class' => VerbFilter::class,
            'actions' => [
                'updateAvatar'  => ['POST'],    // POST para upload avatar
                'create'        => ['POST'],    // POST para upload fotos animal
                'delete'        => ['POST'],    // POST para delete (array photo_ids)
                'viewAvatar'    => ['GET'],     // GET para ver avatar
                'viewAnimal'    => ['GET'],     // GET para ver fotos animal
            ],
        ];

        return $behaviors;
    }


    /**
     * POST /api/files/avatar
     *
     * Atualiza o avatar do utilizador autenticado.
     *
     * Body (multipart/form-data):
     * - file (obrigatório): imagem JPG/PNG (jpg, png)
     *
     * Regras:
     * - O utilizador tem de estar autenticado (Bearer Token).
     * - RBAC: permissão **uploadAvatar** necessária.
     * - Apenas 1 avatar por utilizador (substitui o anterior).
     *
     * Respostas:
     * - 200: { "success": true, "id": <int>, "path": "/uploads/users/xxx.jpg", "message": "Avatar updated" }
     * - 400: ficheiro não enviado ou tipo inválido.
     * - 401: autenticação inválida.
     * - 403: sem permissão **uploadAvatar**.
     */
    public function actionUpdateAvatar()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        if (!Yii::$app->user->can('uploadAvatar')) {
            throw new ForbiddenHttpException('Sem permissão para alterar avatar');
        }

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


    /**
     * POST /api/files
     *
     * Faz upload de múltiplas fotos para um animal específico.
     *
     * Body (multipart/form-data):
     * - animal_id (obrigatório): ID do animal.
     * - files[] (obrigatório): array de imagens.
     *
     * Regras:
     * - O utilizador tem de estar autenticado (Bearer Token).
     * - RBAC: permissão **uploadAnimalPhoto** necessária.
     * - Só o dono do animal pode fazer upload.
     *
     * Respostas:
     * - 201: { "success": true, "uploaded": <int>, "files": [...] }
     * - 400: animal_id ou files[] em falta.
     * - 403: não é dono do animal ou sem permissão **uploadAnimalPhoto**.
     * - 404: animal não encontrado.
     * - 401: autenticação inválida.
     */
    public function actionCreate()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        if (!Yii::$app->user->can('uploadAnimalPhoto')) {
            throw new ForbiddenHttpException('Sem permissão para fazer upload de fotos');
        }

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


    /**
     * POST /api/files/delete
     *
     * Apaga múltiplas fotos de animais.
     *
     * Body (JSON):
     * - photo_ids (obrigatório): array de IDs das fotos [1,2,3]
     *
     * Regras:
     * - O utilizador tem de estar autenticado (Bearer Token).
     * - RBAC: permissão **deleteAnimalPhoto** necessária.
     * - Só o dono das fotos pode apagar.
     *
     * Respostas:
     * - 200: { "success": true, "deleted": <int> }
     * - 400: photo_ids em falta.
     * - 403: sem permissão **deleteAnimalPhoto** ou não é dono das fotos.
     * - 401: autenticação inválida.
     */
    public function actionDelete()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        if (!Yii::$app->user->can('deleteAnimalPhoto')) {
            throw new ForbiddenHttpException('Sem permissão para apagar fotos');
        }

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
     * GET /api/files/avatar/{user_id}
     *
     * Retorna o avatar de um utilizador específico.
     *
     * Path Params:
     * - user_id (obrigatório): ID do utilizador.
     *
     * Regras:
     * - O utilizador tem de estar autenticado (Bearer Token).
     * - RBAC: permissão **viewAvatar** necessária.
     *
     * Respostas:
     * - 200: { "id": <int>, "path": "/uploads/users/xxx.jpg", ... }
     * - 404: avatar não encontrado.
     * - 401: autenticação inválida.
     * - 403: sem permissão **viewAvatar**.
     */
    public function actionViewAvatar($user_id)
    {
        if (!Yii::$app->user->can('viewAvatar')) {
            throw new ForbiddenHttpException('Sem permissão para ver avatares');
        }

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
     * GET /api/files/animal/{animal_id}
     *
     * Lista todas as fotos de um animal específico.
     *
     * Path Params:
     * - animal_id (obrigatório): ID do animal.
     *
     * Regras:
     * - O utilizador tem de estar autenticado (Bearer Token).
     * - RBAC: permissão **viewAnimalPhotos** necessária.
     *
     * Respostas:
     * - 200: { "animal_id": <int>, "count": <int>, "files": [...] }
     * - 404: animal não encontrado.
     * - 401: autenticação inválida.
     * - 403: sem permissão **viewAnimalPhotos**.
     */
    public function actionViewAnimal($animal_id)
    {
        if (!Yii::$app->user->can('viewAnimalPhotos')) {
            throw new ForbiddenHttpException('Sem permissão para ver fotos de animais');
        }

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
