<?php

namespace frontend\controllers;

use common\models\Comment;
use yii;
use common\models\Animal;
use common\models\File;
use common\models\Listing;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;


class ListingsController extends Controller
{


    public function actionAnimal()
    {
        /* return $this->render('animal'); */


       // $listings = Listing::find()->all();

        //dd($listings[0]->animal->animalType->description);

        $query = Listing::find()->where(['status' => 1]);

        $provider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
            'sort' => [
                'defaultOrder' => [
                    'created_at' => SORT_DESC,
                ]
            ],
        ]);

        return $this->render('animal', [
            'provider' => $provider,
            'listings' => $provider->getModels(),
        ]);


        /*

        $listings = Listing::find()
            ->where(['status' => 1]) // Assumindo que '1' = Anúncio Aprovado
            ->orderBy(['created_at' => SORT_DESC]) // Mostrar os mais recentes primeiro
            ->all(); // Pede todos os resultados como um array

            // 2. Enviamos o array de $listings para a view
            return $this->render('animal', [
                'listings' => $listings,
            ]);

        */


    }

    public function actionDetail($id)
    {

        $model = Animal::findOne($id);
        if ($model === null) {
            throw new NotFoundHttpException('O animal que procura não existe.');
        }

        // 3. Vamos buscar os Comments do animal e enviamos para a vista

        // Comentários via relação: Animal → Listing → Comments
        $comments = $model->listing ? $model->listing->comments : [];

        $newComment = new Comment();

        // 4. O Controller ENVIA o $model para a View
        return $this->render('detail', [
            'model' => $model,
            'comments' => $comments,
            'newComment' => $newComment,
        ]);
    }


    public function actionCreateListing()
    {
        // 1. CRIAMOS O MODELO VAZIO
        $model = new Animal();

        if ($this->request->isPost) {

            // 2. Carregar os dados do formulário (name, age, etc.)
            $model->load(Yii::$app->request->post());

            $model->user_id = Yii::$app->user->id;

            // 3. Apanhar as instâncias dos ficheiros
            $model->imageFiles = UploadedFile::getInstances($model, 'imageFiles');

            // 4. Validar o modelo (incluindo as regras dos 'imageFiles')
            if ($model->validate()) {

                // 5. Iniciar uma Transação
                $transaction = Yii::$app->db->beginTransaction();
                try {

                    // 6. Definir o dono e guardar o ANIMAL

                    if (!$model->save(false)) { // 'false' para não validar outra vez
                        throw new \Exception('Falha ao guardar o animal.');
                    }

                    // 7. Guardar os Ficheiros (agora que temos o $model->id)
                    foreach ($model->imageFiles as $file) {

                        // 7a. Gerar caminhos (usando os 'aliases')
                        $userId = $model->user_id;
                        $animalId = $model->id;
                        $basePath = Yii::getAlias('@storagePath') . "/users/{$userId}/animals/{$animalId}";
                        $baseUrl = Yii::getAlias('@storageUrl') . "/users/{$userId}/animals/{$animalId}";
                        \yii\helpers\FileHelper::createDirectory($basePath);

                        // 7b. Guardar o ficheiro no disco
                        $fileName = Yii::$app->security->generateRandomString() . '.' . $file->extension;
                        $path = $basePath . '/' . $fileName;
                        if (!$file->saveAs($path)) {
                            throw new \Exception('Falha ao guardar o ficheiro no disco.');
                        }


                        // 7c. Guardar o registo na tabela 'file'
                        $fileModel = new File();
                        $fileModel->user_id = $userId;
                        $fileModel->animal_id = $animalId;
                        $fileModel->type_id = 1;
                        $fileModel->path = $baseUrl . '/' . $fileName; // O URL público

                        if (!$fileModel->save()) {
                            throw new \Exception('Falha ao guardar o caminho do ficheiro na BD.');
                        }

                    }

                    // 8. (Opcional) Criar o Listing (Anúncio)
                    $listingModel = new Listing();
                    $listingModel->animal_id = $model->id;
                    $listingModel->user_id = Yii::$app->user->id;
                    $listingModel->status = 1; // 0 = Pendente de Aprovação
                    if (!$listingModel->save()) {
                        throw new \Exception('Falha ao criar o anúncio (listing).');
                    }

                    // 9. Se tudo correu bem, 'cometer' a transação
                    $transaction->commit();
                    Yii::$app->session->setFlash('success', 'Anúncio criado com sucesso!');
                    return $this->redirect(['detail', 'id' => $model->id]);

                } catch (\Exception $e) {
                    // 10. Se algo falhou, fazer 'rollback' (desfazer tudo)
                    $transaction->rollBack();
                    Yii::$app->session->setFlash('error', 'Ocorreu um erro: ' . $e->getMessage());
                }
            } else {
                // Erro de validação (ex: nome em branco ou foto em falta)
                // Yii::$app->session->setFlash('error', 'Por favor, corrija os erros no formulário.');
            }
        }

        // 11. (QUANDO A PÁGINA É CARREGADA PELA 1ª VEZ)
        // Enviar o $model (vazio) para a view
        return $this->render('create-listing', [
            'model' => $model,
        ]);
    }


    public function actionUpload()
    {
        $model = new File();

        if (Yii::$app->request->isPost) {
            $model->imageFile = UploadedFile::getInstances($model, 'imageFile');
            if ($model->upload()) {
                // file is uploaded successfully
                return $this->goHome();
            } else {
                dd('error');
                return "error";
            }
        }

        return $this->render('upload', ['model' => $model]);
    }








    public function actionMyListings() {

        return $this->render('my-listings');
    }

}