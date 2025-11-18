<?php

namespace backend\modules\api\controllers;

use yii\filters\auth\QueryParamAuth;
use yii\rest\ActiveController;

/**
 * Default controller for the `api` module
 */
class AnimalController extends ActiveController
{
    public $modelClass = 'common\models\Animal';


    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => QueryParamAuth::className(),
            // 'exept' => ['index', 'view'],
        ];
        return $behaviors;
    }

    public function actionAnimalsComplete()
    {
    $animalsComplete= new$this->modelClass;
    $recs= $animalsComplete::find()->all();
    return['animalsComplete' =>$recs];
    }

//    public function actionNomes()
//    {
//    $pratomodel= new$this->modelClass;
//    $recs= $pratomodel::find()->select(['nome'])->all();
//    return$recs;
//    }

//    public function actionPreco($id)
//    {
//    $pratomodel= new$this->modelClass;
//    $rec= $pratomodel::find()->select(['preco'])
//    ->where(['id' => $id])->one(); //objeto json
//    return$rec;
//    }

//public function actionPrecopornome($nomeprato)
//{
//$pratomodel= new$this->modelClass;
//$recs= $pratomodel::find()->select(['preco'])
//->where(['nome' => $nomeprato])->all(); //array
//return$recs;
//}

//public function actionDelpornome($nomeprato)
//{
//$climodel= new$this->modelClass;
//$recs= $climodel::deleteAll(['nome' => $nomeprato]);
//return$recs;
//}

//public function actionPutprecopornome($nomeprato)
//{
//$novo_preco=\Yii::$app->request->post('preco');
//$climodel= new$this->modelClass;
//$ret= $climodel::findOne(['nome' => $nomeprato]);
//if($ret) {
//$ret->preco= $novo_preco;
//$ret->save();
//}
//else{
//    thrownew\yii\web\NotFoundHttpException("Nome de prato não existe");
//}
//}

//public function actionPostpratovazio()
//{
//$pratomodel= new$this->modelClass;
//$pratomodel->id=0; //é autonumber!
//$pratomodel->nome=' ';
//$pratomodel->descricao=' ';
//$pratomodel->preco=0;
//$pratomodel->disponivel=0;
//$pratomodel->save();
//return$pratomodel;
//}
}
