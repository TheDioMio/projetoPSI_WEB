<?php

use yii\helpers\Html;
use yii\web\YiiAsset;
use yii\widgets\DetailView;

$this->title = 'Detalhes da Raça ' . mb_strtoupper($model->description);
$this->params['breadcrumbs'][] = ['label' => 'Breeds', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
YiiAsset::register($this);
?>
<div class="breed-view">
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
                            'confirm' => 'Tem a certeza que deseja apagar esta raça?',
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
                        'label' => 'Descrição',
                        'attribute' => 'description',
                    ],
                    [
                        'label' => 'Tipo de Animal',
                        'attribute' => 'animalType.description',
                    ],
                ],

            ]) ?>
        </div>
    </div>
</div>