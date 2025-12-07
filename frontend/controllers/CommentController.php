<?php

namespace frontend\controllers;

use Yii;
use common\models\Comment;
use common\models\Listing;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;

class CommentController extends \yii\web\Controller
{

    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            'access' => [
                'class' => AccessControl::class,
                'denyCallback' => function () {
                    Yii::$app->session->setFlash('error', 'Não tem permissões para criar um comentário.');
                    return Yii::$app->response->redirect(['/site/index']);
                },
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['create'],
                        'roles' => ['createComment'],
                    ],
                ],
            ],
        ]);
    }
    public function actionCreate($listing_id)
    {
        $listing = Listing::findOne($listing_id);

        if (!$listing) {
            throw new NotFoundHttpException("Anúncio não encontrado.");
        }

        $model = new Comment();

        if ($model->load(Yii::$app->request->post())) {

            //verifica se é guest mas ponderar mudar para ver a permissão
            if (Yii::$app->user->isGuest) {
                Yii::$app->session->setFlash('error', 'Tem de fazer login para comentar.');
                return $this->redirect(['/site/login']);
            }

            $model->listing_id = $listing_id;
            $model->user_id = Yii::$app->user->id;
            $model->created_at = date('Y-m-d H:i:s');


            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Comentário enviado com sucesso!');
                return $this->redirect(['/listings/detail', 'id' => $listing->animal_id]);
            }
        }

        return $this->redirect(['/listings/detail', 'id' => $listing->animal_id]);

    }
}
