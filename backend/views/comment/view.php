<?php

use yii\helpers\Html;
use yii\helpers\StringHelper;
use yii\web\YiiAsset;
use yii\widgets\DetailView;
$this->title = 'Detalhes do Comentário #' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Comments', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Ver';
YiiAsset::register($this);
?>
<div class="comment-view">
    <div class="card card-outline card-primary shadow-sm">
        <div class="card-header">
            <div class="card-tools float-right">
                <?= Html::a('<i class="fas fa-arrow-left"></i>',
                    ['index'],
                    [
                        'class' => 'btn btn-outline-secondary mr-1',
                        'title' => 'Voltar',
                    ],
                )
                ?>

                <?= Html::a('<i class="fas fa-trash"></i>',
                    ['delete', 'id' => $model->id],
                    [
                        'class' => 'btn btn-danger',
                        'title' => 'Apagar',
                        'data' => [
                            'confirm' => 'Tem a certeza que deseja apagar este comentário?',
                            'method' => 'post',
                        ],
                    ]) ?>
            </div>
        </div>

        <div class="card-body">
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    [
                        'label' => 'Animal Listado',
                        'attribute' => 'listing.animal.name'
                    ],
                    [
                        'label' => 'Autor do Comentário',
                        'attribute' => 'listing.user.username',
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
                ]
            ]) ?>
        </div>
    </div>
</div>