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
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

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
        return $this->render('view', [
            'model' => $this->findModel($id),
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
