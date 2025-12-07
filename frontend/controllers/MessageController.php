<?php

namespace frontend\controllers;

use common\models\Message;
use common\models\User;
use common\models\Listing;
use frontend\models\MessageSearch;
use yii\web\Controller;
use yii;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * MessageController implements the CRUD actions for Message model.
 */
class MessageController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'access' => [
                    'class' => \yii\filters\AccessControl::class,
                    'only' => ['index', 'outbox', 'view', 'create'],
                    'rules' => [
                        [
                            'allow' => true,
                            'roles' => ['@'],
                        ],
                    ],
                ],
                'verbs' => [
                    'class' => VerbFilter::class,
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Message models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new MessageSearch();
        $params = $this->request->queryParams;
        $params['MessageSearch']['receiver_user_id'] = Yii::$app->user->id;
        $dataProvider = $searchModel->search($params);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    public function actionOutbox()
    {
        $searchModel = new MessageSearch();
        $params = $this->request->queryParams;
        $params['MessageSearch']['sender_user_id'] = Yii::$app->user->id;
        $dataProvider = $searchModel->search($params);
        return $this->render('outbox', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Message model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */

    public function actionView($id, $type)
    {


        $model = Message::findOne($id);

        if (!$model) {
            throw new NotFoundHttpException('Mensagem não encontrada.');
        }
        //marcar como lida
        if ($model->receiver_user_id == Yii::$app->user->id && $model->isRead == 0) {
            $model->isRead = 1;
            $model->save(false); // false = não valida novamente
        }

        return $this->render('view', [
            'model' => $model,
            'type' => $type,
        ]);
    }


    /**
     * Creates a new Message model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    //user_id é quem vai receber a mensagem

    public function actionCreate($user_id, $from, $listing_id)
    {
        $receiver = User::findOne($user_id);
        if ($receiver === null) {
            throw new NotFoundHttpException('Utilizador destinatário não encontrado.');
        }

        $model = new Message();

        $model->sender_user_id = Yii::$app->user->id;
        $model->receiver_user_id = $user_id;
        $model->created_at = date('Y-m-d H:i:s');
        $model->isRead = 0;

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {

                $model->sender_user_id = Yii::$app->user->id;
                $model->receiver_user_id = $user_id;
                $model->created_at = date('Y-m-d H:i:s');
                $model->isRead = 0;

                if ($model->save()) {
                    Yii::$app->session->setFlash('success', 'Mensagem enviada com sucesso!');

                    if ($from === "listing") {
                        $listing = Listing::findOne($listing_id);
                        return $this->redirect(['/listings/detail', 'id' => $listing->animal_id]);
                    } elseif ($from === "inbox") {
                        return $this->redirect(['/message/index', 'user_id' => Yii::$app->user->id]);
                    } else {
                        return $this->redirect(['/message/outbox', 'user_id' => Yii::$app->user->id]);
                    }
                }
            }
        }

        return $this->render('create', [
            'model' => $model,
            'receiver' => $receiver,
            'user_id' => $user_id,
            'from' => $from,
            'listing_id' => $listing_id,
        ]);
    }


        /**
     * Updates an existing Message model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Message model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        return $this->redirect(['index', 'user_id' => Yii::$app->user->id]);
    }

    /**
     * Finds the Message model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Message the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Message::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
