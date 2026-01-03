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
        unset($actions['index']);

        return $actions;
    }



    /**
     * GET /api/comments?listing_id=<int>
     *
     * Lista todos os comentários de um listing específico.
     *
     * Query Params:
     * - listing_id (obrigatório): ID do listing para listar comentários.
     *
     * Regras:
     * - O utilizador tem de estar autenticado (Bearer Token).
     * - RBAC: permissão **listComment** necessária.
     *
     * Respostas:
     * - 200: { "success": true, "comments": [...] } - lista ordenada por created_at ASC.
     * - 400: se listing_id não for fornecido.
     * - 401: se não houver autenticação válida.
     * - 403: sem permissão **listComment**.
     */
    public function actionIndex()
    {
        $request   = Yii::$app->request;
        $listingId = $request->get('listing_id');

        if (!Yii::$app->user->can('listComment')) {
            throw new ForbiddenHttpException('Sem permissão para listar comentários');
        }

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


    /**
     * GET /api/comments/<id>
     *
     * Retorna um comentário específico pelo ID.
     *
     * Path Params:
     * - id (obrigatório): ID do comentário.
     *
     * Regras:
     * - O utilizador tem de estar autenticado (Bearer Token).
     * - RBAC: permissão **viewComment** necessária.
     *
     * Respostas:
     * - 200: { "success": true, "comment": {...} } com relações user/listing.
     * - 404: comentário não encontrado.
     * - 401: se não houver autenticação válida.
     * - 403: sem permissão **viewComment**.
     */
    public function actionView($id)
    {
        if (!Yii::$app->user->can('viewComment')) {
            throw new ForbiddenHttpException('Sem permissão para ver comentários');
        }

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


    /**
     * POST /api/comments
     *
     * Cria um novo comentário num listing ativo de um animal.
     *
     * Body (JSON):
     * - animal_id (obrigatório): ID do animal (busca listing ativo automaticamente).
     * - text (obrigatório): texto do comentário.
     *
     * Regras:
     * - O utilizador tem de estar autenticado (Bearer Token).
     * - RBAC: permissão **createComment** necessária.
     * - Deve existir um listing ATIVO para o animal_id fornecido.
     *
     * Respostas:
     * - 201: {
     *     "success": true,
     *     "comment": {
     *         "id": <int>,
     *         "animal_id": <int>,
     *         "user_id": <int>,
     *         "comment_text": "<texto>",
     *         "comment_date": "<data>",
     *         "name_user": "<nome>",
     *         "avatar_user": "<url>"
     *     }
     *   }
     * - 400: animal_id ou text em falta; erros de validação.
     * - 404: listing ativo não encontrado para o animal.
     * - 401: se não houver autenticação válida.
     * - 403: sem permissão **createComment**.
     */
    public function actionCreate()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        if (!Yii::$app->user->can('createComment')) {
            throw new ForbiddenHttpException('Sem permissão para criar comentários');
        }

        $userId = Yii::$app->user->id;
        $data   = Yii::$app->request->bodyParams;

        if (empty($data['animal_id']) || empty($data['text'])) {
            throw new BadRequestHttpException('animal_id e text são obrigatórios');
        }

        // 🔹 Buscar listing ativo do animal
        $listing = Listing::find()
            ->where([
                'animal_id' => $data['animal_id'],
                'status'    => Listing::STATUS_ACTIVE
            ])
            ->one();

        if (!$listing) {
            throw new NotFoundHttpException('Listing ativo não encontrado');
        }

        // 🔹 Criar comentário
        $comment = new Comment();
        $comment->listing_id = $listing->id;
        $comment->user_id    = $userId;
        $comment->text       = $data['text'];
        $comment->created_at = date('Y-m-d H:i:s');

        if (!$comment->save()) {
            throw new BadRequestHttpException(json_encode($comment->errors));
        }

        // 🔹 Carregar relações necessárias
        $comment->refresh();
        $user = $comment->user;

        Yii::$app->response->statusCode = 201;

        return [
            'success' => true,
            'comment' => [
                'id'           => (int)$comment->id,
                'animal_id'    => (int)$listing->animal_id,
                'user_id'      => (int)$comment->user_id,
                'comment_text' => $comment->text,
                'comment_date' => $comment->created_at,
                'name_user'    => $user->name,
                'avatar_user'  => $user->profileImage->path,
            ]
        ];
    }



    /**
     * PUT/PATCH /api/comments/<id>
     *
     * Atualiza o texto de um comentário existente.
     *
     * Path Params:
     * - id (obrigatório): ID do comentário a atualizar.
     *
     * Body (JSON):
     * - text (obrigatório): novo texto do comentário.
     *
     * Regras:
     * - O utilizador tem de estar autenticado (Bearer Token).
     * - RBAC: permissão **updateComment** necessária.
     * - Só o **autor do comentário** pode editar (verificação adicional).
     *
     * Respostas:
     * - 200: {
     *     "success": true,
     *     "comment": {
     *         "id": <int>,
     *         "animal_id": <int>,
     *         "user_id": <int>,
     *         "comment_text": "<texto atualizado>",
     *         "comment_date": "<data>",
     *         "name_user": "<nome>",
     *         "avatar_user": "<url>"
     *     }
     *   }
     * - 400: text em falta; não é autor do comentário; erros de validação.
     * - 404: comentário não encontrado.
     * - 401: se não houver autenticação válida.
     * - 403: sem permissão **updateComment**.
     */
    public function actionUpdate($id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        if (!Yii::$app->user->can('updateComment')) {
            throw new ForbiddenHttpException('Sem permissão para editar comentários');
        }


        $userId = Yii::$app->user->id;
        $data   = Yii::$app->request->bodyParams;

        // Buscar o comentário
        $comment = Comment::findOne($id);
        if (!$comment) {
            throw new NotFoundHttpException('Comentário não encontrado');
        }

        // garantir que só o dono do comentário pode editar
        if ($comment->user_id != $userId) {
            throw new BadRequestHttpException('Não tem permissão para editar este comentário');
        }

        if (empty($data['text'])) {
            throw new BadRequestHttpException('text é obrigatório');
        }

        $comment->text = $data['text'];
        $comment->created_at = date('Y-m-d H:i:s');

        if (!$comment->save()) {
            throw new BadRequestHttpException(json_encode($comment->errors));
        }

        $comment->refresh();
        $user    = $comment->user;
        $listing = $comment->listing; // se tiver relation

        return [
            'success' => true,
            'comment' => [
                'id'           => (int)$comment->id,
                'animal_id'    => (int)$listing->animal_id,
                'user_id'      => (int)$comment->user_id,
                'comment_text' => $comment->text,
                'comment_date' => $comment->created_at,
                'name_user'    => $user->name,
                'avatar_user'  => $user->profileImage->path,
            ]
        ];
    }


    /**
     * DELETE /api/comments/<id>
     *
     * Apaga um comentário específico.
     *
     * Path Params:
     * - id (obrigatório): ID do comentário a apagar.
     *
     * Regras:
     * - O utilizador tem de estar autenticado (Bearer Token).
     * - RBAC: permissão **deleteComment** necessária.
     * - Pode apagar se: **autor do comentário** OU **dono do listing** (verificação adicional).
     *
     * Respostas:
     * - 200: { "success": true, "message": "Comentário apagado com sucesso" }
     * - 404: comentário não encontrado.
     * - 401: se não houver autenticação válida.
     * - 403: sem permissão **deleteComment** OU não autorizado (não é autor nem dono do listing).
     * - 500: erro interno ao apagar.
     */
    public function actionDelete($id)
    {
        $userId = Yii::$app->user->id;

        if (!Yii::$app->user->can('deleteComment')) {
            throw new ForbiddenHttpException('Sem permissão para apagar comentários');
        }

        $comment = Comment::find()
            ->where(['id' => $id])
            ->with('listing')
            ->one();

        if (!$comment) {
            throw new NotFoundHttpException('Comentário não encontrado');
        }

        // Pode apagar se: autor do comentário OU dono do listing
        $isCommentAuthor = $comment->user_id === $userId;
        $isListingOwner  = $comment->listing && $comment->listing->user_id === $userId;

        if (!$isCommentAuthor && !$isListingOwner) {
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
