<?php

namespace backend\modules\api\controllers;

use common\models\Animal;
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

    public function actionCreate() {
        $modelClass = $this->modelClass;
        $model = new $modelClass();

        //Receber os dados do Android
        $body = Yii::$app->request->bodyParams;

        //Validações básicas do Animal
        $animalId = $body['animal_id'] ?? null;
        if (!$animalId) {
            throw new BadRequestHttpException("O ID do animal é obrigatório.");
        }

        $animal = Animal::findOne($animalId);
        if (!$animal) {
            throw new NotFoundHttpException("Animal não encontrado com o ID: " . $animalId);
        }

        //Preencher as colunas REAIS da tabela application
        $model->animal_id = $animal->id;
        $model->target_user_id = $animal->user_id;
        $model->user_id = Yii::$app->user->id; // ID de quem está logado (Token)

        // Preencher description e motive
        $model->description = $body['motive'] ?? 'Sem motivo';
        $model->motive = $body['motive'] ?? 'Sem motivo';

        $dadosExtra = [
            'age' => $body['age'] ?? null,
            'contact' => $body['contact'] ?? null,
            'motive' => $body['motive'] ?? null,
            'home' => $body['home'] ?? null,
            'bills' => $body['bills'] ?? null,
            'timeAlone' => $body['timeAlone'] ?? null,
            'children' => $body['children'] ?? null,
            'followUp' => $body['followUp'] ?? null,
        ];
        if ($model->hasAttribute('data')) {
            $model->data = json_encode($dadosExtra);
        } else {
            $model->load($body, '');
        }

        // 5. Defaults
        $model->created_at = date('Y-m-d H:i:s');
        $model->statusDate = date('Y-m-d'); // Data de hoje
        $model->isRead = 0;
        $model->type = 1; // 1 = TYPE_ADOPTION
        $model->status = "Pendente";

        if ($model->save()) {
            $model->refresh();

            Yii::$app->response->statusCode = 201;
            return $model;
        } else {
            Yii::$app->response->statusCode = 422;
            return $model->errors;
        }
    }
}
