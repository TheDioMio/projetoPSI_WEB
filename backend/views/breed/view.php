<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\Breed $model */

$this->title = 'Detalhes da Raça '. mb_strtoupper($model->description);
$this->params['breadcrumbs'][] = ['label' => 'Breeds', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="breed-view">
    <div class="card card-outline card-primary shadow-sm">
        <div class="card-header">
            <div class="card-tools float-right">
                <?= Html::a('<i class="fas fa-arrow-left"></i> Voltar à Lista', ['index'], ['class' => 'btn btn-default btn-sm']) ?>
                <?= Html::a('Delete', ['delete', 'id' => $model->id], [
                    'class' => 'btn btn-danger btn-sm',
                    'data' => [
                        'confirm' => 'Tem a certeza que deseja eliminar esta raça?',
                        'method' => 'post',
                    ],
                ]) ?>
            </div>
        </div>
        <div class="card-body">
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'id',
                    'description',
                    [
                        'label' => 'Tipo de Animal',
                        'attribute' => 'animalType.description',
                    ],
                ],
            ]) ?>
        </div>
    </div>
</div>