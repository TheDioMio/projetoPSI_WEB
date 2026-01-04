<?php

namespace backend\modules\api\controllers;

use common\models\Animal;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\Query;
use yii\filters\auth\HttpBearerAuth;
use yii\rest\ActiveController;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;

class ApplicationController extends ActiveController {
    public $modelClass = 'backend\modules\api\models\Application';

    // Autenticação
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::class,
        ];
        return $behaviors;
    }

    /*
     * DESATIVAR AÇÕES PADRÃO DO ACTIVECONTROLLER
     * Isto é obrigatório para garantir que o Yii usa o nosso actionCreate
     * e não a função automática que ignora o target_user_id. (Coisa que demorei 6h a descobrir)
     *
     */
    public function actions()
    {
        $actions = parent::actions();
        unset($actions['create']); // Desliga o create automático
        unset($actions['update']); // Desliga o update automático
        return $actions;
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

        Yii::$app->response->statusCode = 200;

        return [
            'success' => true,
            'message' => 'Candidatura apagada com sucesso',
        ];
    }

    public function actionCreate() {
        // 1. Refresh ao Schema da BD
        Yii::$app->db->schema->refresh();

        $modelClass = $this->modelClass;
        $model = new $modelClass();

        // 2. Receber dados
        $body = Yii::$app->request->bodyParams;

        //Se o 'data' vier como string JSON dentro do JSON
        if (isset($body['data']) && is_string($body['data'])) {
            $decodedData = json_decode($body['data'], true);
            if (is_array($decodedData)) {
                $body = array_merge($body, $decodedData);
            }
        }

        $animalId = $body['animal_id'] ?? null;

        if (!$animalId) throw new BadRequestHttpException("Falta animal_id");

        // 3. Buscar Dono (SQL Puro)
        $donoId = (new Query())
            ->select(['user_id'])
            ->from('animal')
            ->where(['id' => $animalId])
            ->scalar();

        if (!$donoId) throw new BadRequestHttpException("Animal sem dono!");

        // 4. Carregar dados do Android
        $model->load($body, '');

        //ATRIBUIÇÃO FORÇADA dos dados do modelo
        $model->setAttributes([
            'animal_id' => $animalId,
            'user_id' => Yii::$app->user->id,
            'target_user_id' => (int)$donoId, // Forçamos que seja Inteiro
            'type' => 1,
            'status' => 0,
            'isRead' => 0,
            'created_at' => date('Y-m-d H:i:s'),
            'statusDate' => date('Y-m-d'),
        ], false);

        // Preencher descrição se faltar
        if (empty($model->description)) {
            $model->description = $body['motive'] ?? 'Sem motivo';
        }

        //GUARDAR
        if ($model->save()) {
            $model->refresh();
            Yii::$app->response->statusCode = 201;
            return $model;
        } else {
            Yii::$app->response->statusCode = 422;
            return [
                'errors' => $model->errors,
                'tentou_gravar_dono' => $donoId
            ];
        }
    }
}