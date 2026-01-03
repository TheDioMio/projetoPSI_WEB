<?php

namespace backend\controllers;

use common\models\Animal;
use backend\models\AnimalSearch;
use common\models\AnimalAge;
use common\models\AnimalSize;
use common\models\AnimalType;
use common\models\Breed;
use common\models\File;
use common\models\Listing;
use common\models\User;
use common\models\Vaccination;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\web\UploadedFile;


class AnimalController extends Controller
{
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'access' => [
                    'class' => AccessControl::class,
                    'denyCallback' => function () {
                        if (Yii::$app->user->can('loginBackend')) {
                            return Yii::$app->response->redirect(['/site/index']);
                        }
                        return Yii::$app->response->redirect(['/site/login']);
                    },
                    'except' => ['error'],
                    'rules' => [
                        [
                            'allow' => true,
                            'roles' => ['loginBackend'],
                        ],
                    ],
                ],
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                        'logout' => ['POST'],
                    ],
                ],
            ]
        );
    }

    public function beforeAction($action)
    {
        if (!parent::beforeAction($action)) {
            return false;
        }
        // Lógica para passar o utilizador para o LAYOUT/SIDEBAR
        $this->view->params['userLogado'] = Yii::$app->user->identity;
        return true;
    }

    public function actionIndex()
    {
        $searchModel = new AnimalSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id){
        $model = $this->findModel($id);

        // --- LÓGICA DE IMAGENS ---
        $images = $model->files;
        $totalImages = count($images);
        $carouselIndicators = '';
        $carouselItems = '';
        $i = 0;

        if ($totalImages > 0) {
            foreach ($images as $image) {
                $isActive = ($i === 0) ? 'active' : '';

                // Como as imagens são guardadas no caminho de frontend, temos que substituir para o backend:
                $rawUrl = $image->url;
                $imageUrl = str_replace('/backend/web', '/frontend/web', $rawUrl);

                if (strpos($imageUrl, 'http') === false && substr($imageUrl, 0, 1) !== '/') {
                    $imageUrl = '/' . $imageUrl;
                }

                $carouselIndicators .= '<li data-target="#animalCarousel" data-slide-to="' . $i . '" class="' . $isActive . '"></li>';
                $carouselItems .= '<div class="carousel-item ' . $isActive . '">';
                $carouselItems .= Html::img($imageUrl, [
                    'class' => 'd-block w-100',
                    'alt' => $model->name,
                    'style' => 'height: 400px; object-fit: cover; width: 100%;'
                ]);
                $carouselItems .= '</div>';
                $i++;
            }
        }

        return $this->render('view', [
            'model' => $model,
            'totalImages' => $totalImages,
            'carouselIndicators' => $carouselIndicators,
            'carouselItems' => $carouselItems,
        ]);
    }
    public function actionUpdate($id) {
        $model = $this->findModel($id);

        $model->scenario = 'update';

        // Recarregar Dropdowns
        $animalTypes = ArrayHelper::map(AnimalType::find()->orderBy('description')->all(), 'id', 'description');
        $idades = ArrayHelper::map(AnimalAge::find()->all(), 'id', 'description');
        $portes = ArrayHelper::map(AnimalSize::find()->all(), 'id', 'description');
        $vacinas = ArrayHelper::map(Vaccination::find()->all(), 'id', 'description');
        $users = ArrayHelper::map(User::find()->all(), 'id', 'username');

        $breedsByType = [];
        $breeds = Breed::find()->orderBy('description')->all();
        foreach ($breeds as $breed) {
            $breedsByType[$breed->animal_type_id][$breed->id] = $breed->description;
        }

        // Imagens existentes
        $existingImages = File::find()->where(['animal_id' => $model->id])->all();

        if ($this->request->isPost) {
            $model->load($this->request->post());

            $model->imageFiles = UploadedFile::getInstances($model, 'imageFiles');

            if ($model->validate()) {
                if ($model->save()) {
                    // Upload de NOVAS imagens (se existirem)
                    if (count($model->imageFiles) > 0) {
                        $basePath = Yii::getAlias('@frontend/web/uploads/animals/' . $model->id);
                        if (!is_dir($basePath)) { mkdir($basePath, 0777, true); }

                        foreach ($model->imageFiles as $file) {
                            $filename = uniqid() . '.' . $file->extension;
                            $path = $basePath . '/' . $filename;
                            if ($file->saveAs($path)) {
                                $fileDb = new File();
                                $fileDb->animal_id = $model->id;
                                $fileDb->user_id = $model->user_id;
                                $fileDb->type_id = 1;
                                $fileDb->path = '/uploads/animals/' . $model->id . '/' . $filename;
                                $fileDb->created_at = date('Y-m-d H:i:s');
                                $fileDb->save(false);
                            }
                        }
                    }

                    Yii::$app->session->setFlash('success', 'Atualizado com sucesso.');
                    return $this->redirect(['view', 'id' => $model->id]);
                }
            }
        }

        return $this->render('update', [
            'model' => $model,
            'existingImages' => $existingImages,
            'animalTypes' => $animalTypes,
            'breedsByType' => $breedsByType,
            'idades' => $idades,
            'portes' => $portes,
            'vacinas' => $vacinas,
            'users' => $users,
        ]);
    }

    public function actionDelete($id) {
        $model = $this->findModel($id);

        if ($model->listing) {
            $model->listing->status = Listing::STATUS_DELETED;
            $model->listing->save(false);
        }

        $model->status = Animal::STATUS_DELETED;
        $model->save(false);

        Yii::$app->session->setFlash('success', 'Anúncio e animal apagados com sucesso.');
        return $this->redirect(['index']);
    }

    protected function findModel($id)
    {
        if (($model = Animal::findOne(['id' => $id])) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionDeleteImage($id, $animal_id)
    {
        $fileDb = File::findOne($id);

        if ($fileDb) {
            $frontendPath = dirname(dirname(__DIR__)) . '/frontend/web';
            $filePath = $frontendPath . $fileDb->path;

            if (file_exists($filePath)) {
                unlink($filePath);
            }

            $fileDb->delete();
            Yii::$app->session->setFlash('success', 'Imagem apagada com sucesso.');
        }

        return $this->redirect(['update', 'id' => $animal_id]);
    }
}
