<?php

use yii\helpers\Html;
use yii\web\YiiAsset;
use yii\widgets\DetailView;

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Animal Types', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
YiiAsset::register($this);
?>
<div class="animal-type-view">
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
                    ['delete', 'id' => $model->id], [
                    'class' => 'btn btn-danger mr-1',
                    'title' => 'Apagar',
                    'data' => [
                        'confirm' => 'Tem a certeza que deseja apagar este tipo de animal?',
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
                ],
            ]) ?>
        </div>
    </div>
</div>