<?php

namespace backend\modules\api\controllers;

use backend\modules\api\models\Comment;
use common\models\Listing;
use Yii;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\VerbFilter;
use yii\rest\ActiveController;
use yii\web\BadRequestHttpException;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\ServerErrorHttpException;

class CommentController extends ActiveController
{
    public $modelClass = 'backend\modules\api\models\Comment';


    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::class,
        ];

        $behaviors['verbs'] = [
            'class' => VerbFilter::class,
            'actions' => [
                'index'  => ['GET'],
                'view'   => ['GET'],
                'create' => ['POST'],
                'update' => ['PUT', 'PATCH'],
                'delete' => ['DELETE'],
            ],
        ];

        return $behaviors;
    }

    public function beforeAction($action)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        return parent::beforeAction($action);
    }

    //Utilizo para subescrever
    public function actions()
    {
        $actions = parent::actions();
        unset($actions['view']);
        unset($actions['create']);
        unset($actions['update']);
        unset($actions['delete']);
        // desativa o index padrão
        unset($actions['index']);

        return $actions;
    }

    //Ponderar não implementar
    public function actionIndex()
    {
        $request   = Yii::$app->request;
        $listingId = $request->get('listing_id');

        if (!$listingId) {
            throw new BadRequestHttpException('listing_id é obrigatório');
        }

        $comments = Comment::find()
            ->where(['listing_id' => $listingId])
            ->with(['user', 'listing'])
            ->orderBy(['created_at' => SORT_ASC])
            ->all();

        return [
            'success'  => true,
            'comments' => $comments,
        ];
    }



    public function actionView($id)
    {
        $comment = Comment::find()
            ->where(['id' => $id])
            ->with(['user', 'listing'])
            ->one();

        if (!$comment) {
            throw new NotFoundHttpException('Comentário não encontrado');
        }

        return [
            'success' => true,
            'comment' => $comment,
        ];
    }


    public function actionCreate()
    {
        $userId = Yii::$app->user->id;
        $data   = Yii::$app->request->bodyParams;

        if (empty($data['listing_id']) || empty($data['text'])) {
            throw new BadRequestHttpException('listing_id e text são obrigatórios');
        }

        $listing = Listing::findOne($data['listing_id']);
        if (!$listing) {
            throw new NotFoundHttpException('Listing não encontrado');
        }

        $comment = new Comment();
        $comment->listing_id = $listing->id;
        $comment->user_id    = $userId;
        $comment->text       = $data['text'];
        $comment->created_at = date('Y-m-d H:i:s');

        if (!$comment->save()) {
            throw new BadRequestHttpException(json_encode($comment->errors));
        }

        Yii::$app->response->statusCode = 201;

        return [
            'success' => true,
            'comment' => $comment,
        ];
    }


    public function actionUpdate($id)
    {
        $userId = Yii::$app->user->id;
        $data   = Yii::$app->request->bodyParams;

        $comment = Comment::findOne($id);
        if (!$comment) {
            throw new NotFoundHttpException('Comentário não encontrado');
        }

        if ($comment->user_id !== $userId) {
            throw new ForbiddenHttpException('Não autorizado a editar este comentário');
        }

        if (isset($data['text'])) {
            $comment->text = $data['text'];
        }

        if (!$comment->save()) {
            throw new BadRequestHttpException(json_encode($comment->errors));
        }

        return [
            'success' => true,
            'comment' => $comment,
        ];
    }


    public function actionDelete($id)
    {
        $userId = Yii::$app->user->id;

        $comment = Comment::findOne($id);
        if (!$comment) {
            throw new NotFoundHttpException('Comentário não encontrado');
        }

        if ($comment->user_id !== $userId) {
            throw new ForbiddenHttpException('Não autorizado a apagar este comentário');
        }

        if ($comment->delete() === false) {
            throw new ServerErrorHttpException('Erro ao apagar comentário');
        }

        return [
            'success' => true,
            'message' => 'Comentário apagado com sucesso',
        ];
    }




}
