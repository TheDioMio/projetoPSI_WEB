<?php

use yii\helpers\Html;
use yii\web\YiiAsset;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\Listing $model */

$this->title = 'Detalhes da Listagem #'.$model->id;
$this->params['breadcrumbs'][] = ['label' => 'Listings', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
YiiAsset::register($this);
?>
<div class="listing-view">
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

                <?= Html::a('<i class="fas fa-edit"></i>',
                    ['update', 'id' => $model->id],
                    [
                        'class' => 'btn btn-primary mr-1',
                        'title' => 'Editar',
                    ],
                )
                ?>

                <?= Html::a('<i class="fas fa-trash"></i>',
                    ['delete', 'id' => $model->id],
                    [
                        'class' => 'btn btn-danger',
                        'title' => 'Apagar',
                        'data' => [
                            'confirm' => 'Tem a certeza que deseja apagar esta listagem?',
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
                        'format' => 'raw',
                        'value' => function ($model) {
                            if (!$model->animal) {
                                return '<span class="text-muted">(Sem animal associado)</span>';
                            }
                            return Html::a(
                                $model->animal->name,
                                ['animal/view', 'id' => $model->animal->id],
                                [
                                    'target' => '_blank',
                                ]
                            );
                        },
                    ],
                    [
                        'label' => 'Autor da Listagem',
                        'format' => 'raw',
                        'value' => function ($model) {
                            if (!$model->user) {
                                return '<span class="text-muted">(Sem dono associado)</span>';
                            }
                            return Html::a(
                                $model->user->username, //Texto que aparece no botão (nome do dono)
                                ['user/view', 'id' => $model->user->id], //A rota para onde vai (backend/user/view)
                                [
                                    'target' => '_blank',
                                ]
                            );
                        },
                    ],

                    [
                        'label' => 'Descrição',
                        'attribute' => 'description',
                        'format' => 'ntext',
                    ],
                    [
                        'label' => 'Visualizações',
                        'attribute' => 'views',
                    ],
                    [
                        'label' => 'Data de Criação',
                        'attribute' => 'created_at',
                    ],
                ],
            ]) ?>
        </div>
    </div>
</div>


