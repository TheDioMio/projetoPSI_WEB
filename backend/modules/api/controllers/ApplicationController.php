<?php

namespace backend\modules\api\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\auth\HttpBearerAuth;
use yii\rest\ActiveController;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;

class ApplicationController extends ActiveController {
    public $modelClass = 'backend\modules\api\models\Application';

    //Autenticação
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::class,
        ];
        return $behaviors;
    }

    /*
     * Candidaturas ENVIADAS pelo user (Sent)
     * Endpoint: GET /api/applications/sent
     */
    public function actionSent()
    {
        $userId = Yii::$app->user->id;

        // Procura onde o user_id (remetente) é o user logado
        $query = $this->modelClass::find()
            ->where(['user_id' => $userId])
            ->orderBy(['created_at' => SORT_DESC]); // Mais recentes primeiro

        return new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => 20],
        ]);
    }

    /*
     * Candidaturas RECEBIDAS pelo user (Received)
     * Endpoint: GET /api/applications/received
     */
    public function actionReceived(){
        $userId = Yii::$app->user->id;

        // Procura onde o target_user_id (destinatário) é o user logado
        $query = $this->modelClass::find()
            ->where(['target_user_id' => $userId])
            ->orderBy(['created_at' => SORT_DESC]);

        return new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => 20],
        ]);
    }


    /*
     * Dar update da candidatura (aprovada/rejeitada)
     * Endpoint: PUT /api/applications/{id}/status/
     */
    public function actionUpdate($id) {
        $model = $this->modelClass::findOne($id);

        if (!$model) {
            throw new NotFoundHttpException("Candidatura não encontrada");
        }

        // Carregar os dados (o '' serve para ler o JSON diretamente da raiz)
        $model->load(Yii::$app->getRequest()->getBodyParams(), '');

        if ($model->save()) {
            return $model;
        }

        return $model;
    }

    public function actionDelete($id){
        $modelClass = $this->modelClass;
        $model = $modelClass::findOne($id);

        if (!$model) {
            throw new NotFoundHttpException('Candidatura não encontrada.');
        }

        // valida permissões
        $this->checkAccess('delete', $model);

        if ($model->delete() === false) {
            throw new ServerErrorHttpException('Erro ao apagar a mensagem.');
        }

        Yii::$app->response->statusCode = 204;
    }
}
