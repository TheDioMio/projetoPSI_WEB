<?php

use common\models\Comment;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
$this->title = 'Gestão de Comentários';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="comment-index container-fluid">
    <div class="card card-outline card-primary shadow-sm">
        <div class="card-header">
<!--            <div class="card-tools float-right">-->
<!--                --><?php //= Html::a('<i class="fas fa-plus-circle"></i> Criar Comentário', ['create'], ['class' => 'btn btn-success btn-sm']) ?>
<!--            </div>-->
            <!---->
        </div>
        <div class="card-body p-0">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'tableOptions' => ['class' => 'table table-hover table-striped table-sm'],
                'layout' => "{items}\n{summary}\n{pager}",
                'columns' => [
                    [
                        'label' => 'Animal Listado',
                        'attribute' => 'animal_name',
                        'value' => 'listing.animal.name'
                    ],
                    [
                        'label' => 'Autor do Comentário',
                        'attribute' => 'user_username',
                        'value' => 'listing.user.username',
                    ],
                    [
                        'label' => 'Conteúdo',
                        'attribute' => 'text',
                        'format'=> 'ntext',
                    ],
                    [
                        'label' => 'Data de Publicação',
                        'attribute' => 'created_at',
                    ],
                    [
                        'class' => ActionColumn::class,
                        'template' => '{view} {delete}',
//                        'buttons' => [ TENTATIVA DE MUDAR A MENSAGEM DE WARNING DO BOTÃO DE DELETE, NÃO DEU...
//                            'delete' => function ($url, $model, $key) {
//                                return Html::a('<i class="far fa-trash-alt"></i>', $url, [
//                                    'title' => 'Eliminar',
//                                    'aria-label' => 'Eliminar',
//                                    'data-confirm' => 'Tem a certeza que deseja eliminar este comentário? (Esta é a nova mensagem!)',
//                                    'data-method' => 'post',
//                                ]);
//                            },
//                        ],
                ],]
            ]); ?>
        </div>
    </div>
</div>