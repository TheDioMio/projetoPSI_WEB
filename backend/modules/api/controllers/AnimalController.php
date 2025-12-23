<?php

namespace backend\modules\api\controllers;

use backend\modules\api\models\Animal;
use common\models\Listing;
use yii\data\ActiveDataProvider;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\auth\QueryParamAuth;
use yii\rest\ActiveController;

/**
 * Default controller for the `api` module
 */
class AnimalController extends ActiveController
{
    public $modelClass = 'backend\modules\api\models\Animal';


    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::className(),
            // 'exept' => ['index', 'view'],
        ];
        return $behaviors;
    }


    //Utilizo para subescrever o index
    public function actions()
    {
        $actions = parent::actions();

        // desativa o index padrão
        unset($actions['index']);

        return $actions;
    }



    //--------------------------------ACTION INDEX DEVOLVE TODOS OS ANIMAIS/ ANUNCIOS ACTIVOS ---------------------- COM PAGINAÇÃO POIS PODEM SER MUITOS
    /**
     * GET /animals
     */
    public function actionIndex()
    {
        $query = Animal::find()
            ->where(['status' => Listing::STATUS_ACTIVE])
            ->with('files',
                'listing',
                'listing.comments.user.profileImage',
                'breed',
                'animalType',
                'animalAge',
                'size',
                'vaccination',
                'user');

        // 🔎 filtros opcionais (ex: type, size)
        $request = \Yii::$app->request;

        if ($type = $request->get('type')) {
            $query->andWhere(['type' => $type]);
        }

        if ($size = $request->get('size')) {
            $query->andWhere(['size' => $size]);
        }

        return new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
                'pageSizeLimit' => [5, 50],
            ],
            'sort' => [
                'defaultOrder' => [
                    'created_at' => SORT_DESC,
                ],
            ],
        ]);
    }

//    public function actionAnimalsComplete()
//    {
//    $animalsComplete= new$this->modelClass;
//    $recs= $animalsComplete::find()->all();
//    return['animalsComplete' =>$recs];
//    }


}
