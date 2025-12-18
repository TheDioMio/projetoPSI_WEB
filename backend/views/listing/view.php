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
                <p>
                    <?= Html::a('<i class="fas fa-arrow-left"></i> Voltar à Lista', ['index'], ['class' => 'btn btn-default btn-sm']) ?>
                    <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary btn-sm']) ?>
                    <?= Html::a('Delete', ['delete', 'id' => $model->id], [
                        'class' => 'btn btn-danger btn-sm',
                        'data' => [
                            'confirm' => 'Tem a certeza que deseja eliminar esta listagem?',
                            'method' => 'post',
                        ],
                    ]) ?>
                </p>
            </div>
        </div>
        <div class="card-body">
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    [
                        'label' => 'Animal Listado',
                        'attribute' => 'animal.name',

                    ],
                    [
                        'label' => 'Autor da Listagem',
                        'attribute' => 'user.name',
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


