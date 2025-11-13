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
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * AnimalController implements the CRUD actions for Animal model.
 */
class AnimalController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Animal models.
     *
     * @return string
     */

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

    /**
     * Displays a single Animal model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Animal model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Animal();

        $sizes = AnimalSize::find()->select(['id','description'])->indexBy('id')->asArray()->all();
        $breeds = Breed::find()->select(['id','description'])->indexBy('id')->asArray()->all();
        $animalTypes = AnimalType::find()->select(['id','description'])->indexBy('id')->asArray()->all();
        $users = User::find()->select(['id', 'name'])->indexBy('id')->asArray()->all();
        $vaccines = Vaccination::find()->select(['id', 'description'])->indexBy('id')->asArray()->all();
        $ages = AnimalAge::find()->select(['id', 'description'])->indexBy('id')->asArray()->all();


        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
            'sizes' => $sizes,
            'breeds' => $breeds,
            'animalTypes' => $animalTypes,
            'users' => $users,
            'vaccines' => $vaccines,
            'ages' => $ages,
        ]);
    }

    /**
     * Updates an existing Animal model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
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

    /**
     * Deletes an existing Animal model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Animal model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Animal the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Animal::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
