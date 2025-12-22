<?php

namespace backend\modules\api\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\auth\HttpBearerAuth;
use yii\web\ForbiddenHttpException;

class MessageController extends \yii\rest\ActiveController
{
    public $modelClass = 'backend\modules\api\models\Message';


    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::className(),
        ];
        return $behaviors;
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

        return $actions;
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
