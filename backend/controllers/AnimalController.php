<?php

namespace backend\controllers;

use common\models\Animal;
use backend\models\AnimalSearch;
use common\models\AnimalAge;
use common\models\AnimalSize;
use common\models\AnimalType;
use common\models\Breed;
use common\models\Listing;
use common\models\User;
use common\models\Vaccination;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\Html;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Url;


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

    public function actionView($id)
    {
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

//    public function actionCreate()
//    {
//        $model = new Animal();
//
//        $sizes = AnimalSize::find()->select(['id','description'])->indexBy('id')->asArray()->all();
//        $breeds = Breed::find()->select(['id','description'])->indexBy('id')->asArray()->all();
//        $animalTypes = AnimalType::find()->select(['id','description'])->indexBy('id')->asArray()->all();
//        $users = User::find()->select(['id', 'name'])->indexBy('id')->asArray()->all();
//        $vaccines = Vaccination::find()->select(['id', 'description'])->indexBy('id')->asArray()->all();
//        $ages = AnimalAge::find()->select(['id', 'description'])->indexBy('id')->asArray()->all();
//
//
//        if ($this->request->isPost) {
//            if ($model->load($this->request->post()) && $model->save()) {
//                return $this->redirect(['view', 'id' => $model->id]);
//            }
//        } else {
//            $model->loadDefaultValues();
//        }
//
//
//        return $this->render('create', [
//            'model' => $model,
//            'sizes' => $sizes,
//            'breeds' => $breeds,
//            'animalTypes' => $animalTypes,
//            'users' => $users,
//            'vaccines' => $vaccines,
//            'ages' => $ages,
//        ]);
//    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $sizes = AnimalSize::find()->select(['id','description'])->indexBy('id')->asArray()->all();
        $breeds = Breed::find()->select(['id','description'])->indexBy('id')->asArray()->all();
        $animalTypes = AnimalType::find()->select(['id','description'])->indexBy('id')->asArray()->all();
        $users = User::find()->select(['id', 'name'])->indexBy('id')->asArray()->all();
        $vaccines = Vaccination::find()->select(['id', 'description'])->indexBy('id')->asArray()->all();
        $ages = AnimalAge::find()->select(['id', 'description'])->indexBy('id')->asArray()->all();

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
            'sizes' => $sizes,
            'breeds' => $breeds,
            'animalTypes' => $animalTypes,
            'users' => $users,
            'vaccines' => $vaccines,
            'ages' => $ages,
        ]);
    }

    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        return $this->redirect(['index']);
    }

    protected function findModel($id)
    {
        if (($model = Animal::findOne(['id' => $id])) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
