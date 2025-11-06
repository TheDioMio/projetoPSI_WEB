<?php

use common\models\Comment;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
/** @var yii\web\View $this */
/** @var app\models\CommentSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */
$this->title = 'Gestão de Comentários';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="comment-index container-fluid">
    <div class="card card-outline card-primary shadow-sm">
        <div class="card-header">
            <div class="card-tools float-right">
                <?= Html::a('<i class="fas fa-plus-circle"></i> Criar Comentário', ['create'], ['class' => 'btn btn-success btn-sm']) ?>
            </div>
        </div>
        <div class="card-body p-0">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'tableOptions' => ['class' => 'table table-hover table-striped table-sm'],
                'layout' => "{items}\n{summary}\n{pager}",
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    'id',
                    'listing_id',
                    'user_id',
                    'text:ntext',
                    'created_at',
                    [
                        'class' => ActionColumn::class,
                        'template' => '{view} {delete}',
                        'urlCreator' => function ($action, Comment $model, $key, $index, $column) {
                            return Url::toRoute([$action, 'id' => $model->id]);
                        }
                    ],
                ],
            ]); ?>
        </div>
    </div>
</div>