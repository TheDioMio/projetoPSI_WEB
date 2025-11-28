<?php

namespace frontend\controllers;

use Yii;
use common\models\Comment;
use common\models\Listing;
use yii\web\NotFoundHttpException;

class CommentController extends \yii\web\Controller
{
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

            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Comentário enviado com sucesso!');
                return $this->redirect(['/site/detail', 'id' => $listing->animal_id]);
            }
        }

        return $this->redirect(['/site/detail', 'id' => $listing->animal_id]);

    }
}
