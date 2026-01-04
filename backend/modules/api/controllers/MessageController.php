<?php

namespace backend\modules\api\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\auth\HttpBearerAuth;
use yii\web\BadRequestHttpException;
use yii\web\ForbiddenHttpException;
use yii\filters\VerbFilter;

class MessageController extends \yii\rest\ActiveController
{
    public $modelClass = 'backend\modules\api\models\Message';


    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::className(),
        ];

        $behaviors['verbs'] = [
            'class' => VerbFilter::class,
            'actions' => [
                'index'  => ['GET'],
                'view'   => ['GET'],
                'create' => ['POST'],
                'update' => ['PUT', 'PATCH'],
                //'delete' => ['DELETE'],
            ],
        ];

        return $behaviors;
    }

    public function afterAction($action, $result)
    {
        if ($action->id === 'delete') {
            return $result; // NÃO deixar o Yii forçar 204
        }

        return parent::afterAction($action, $result);
    }

    public function actions()
    {
        $actions = parent::actions();

        // Filtrar lista para só mensagens do utilizador autenticado
        $actions['index']['prepareDataProvider'] = function ($action) {
            $userId = Yii::$app->user->id;

            $query = $this->modelClass::find()
                ->where(['sender_user_id' => $userId])
                ->orWhere(['receiver_user_id' => $userId])
                ->orderBy(['created_at' => SORT_DESC]);

            return new ActiveDataProvider([
                'query' => $query,
                'pagination' => ['pageSize' => 20],
            ]);
        };

        unset($actions['create']);
        unset($actions['update']);

        return $actions;
    }

    public function actionCreate() {
        $modelClass = $this->modelClass;
        $model = new $modelClass();

        $body = Yii::$app->request->bodyParams;

        $model->receiver_user_id = $body['receiver_user_id'] ?? null;
        $model->subject = $body['subject'] ?? null;
        $model->text = $body['text'] ?? null;

        // sender vem SEMPRE do token
        $model->sender_user_id = Yii::$app->user->id;

        // defaults
        $model->created_at = date('Y-m-d H:i:s');
        $model->isRead = 0;

        if (!$model->validate()) {
            Yii::$app->response->statusCode = 422;
            return $model->errors;
        }

        if (!$model->save(false)) {
            throw new BadRequestHttpException('Erro ao guardar a mensagem.');
        }

        Yii::$app->response->statusCode = 201;
        return $model;
    }

    public function actionUpdate($id)
    {
        $modelClass = $this->modelClass;
        $model = $modelClass::findOne($id);

        if (!$model) {
            throw new \yii\web\NotFoundHttpException('Mensagem não encontrada.');
        }

        $this->checkAccess('update', $model);

        $body = Yii::$app->request->bodyParams;

        // só permitir estes campos
        if (array_key_exists('subject', $body)) {
            $model->subject = $body['subject'];
        }
        if (array_key_exists('text', $body)) {
            $model->text = $body['text'];
        }

        if (!$model->validate(['subject', 'text'])) {
            Yii::$app->response->statusCode = 422;
            return $model->errors;
        }

        if (!$model->save(false, ['subject', 'text'])) {
            throw new \yii\web\ServerErrorHttpException('Erro ao atualizar a mensagem.');
        }

        return $model;
    }

    public function actionDelete($id)
    {
        $modelClass = $this->modelClass;
        $model = $modelClass::findOne($id);

        if (!$model) {
            throw new \yii\web\NotFoundHttpException('Mensagem não encontrada.');
        }

        // valida permissões
        $this->checkAccess('delete', $model);

        if ($model->delete() === false) {
            throw new \yii\web\ServerErrorHttpException('Erro ao apagar a mensagem.');
        }

        Yii::$app->response->statusCode = 200;

        return [
            'success' => true,
            'message' => 'Mensagem apagada com sucesso'
        ];
    }



    public function checkAccess($action, $model = null, $params = [])
    {
        // Impede ver/alterar/apagar mensagens que não sejam do utilizador autenticado
        if (in_array($action, ['view', 'update', 'delete'], true) && $model !== null) {
            $userId = Yii::$app->user->id;

            $isMine = ($model->sender_user_id == $userId) || ($model->receiver_user_id == $userId);
            if (!$isMine) {
                throw new ForbiddenHttpException('Acesso proibido.');
            }
        }
    }

}

