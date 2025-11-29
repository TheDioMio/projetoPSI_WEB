<?php

use yii\helpers\Html;

$this->title = 'Atualizar Raça ' . mb_strtoupper($model->description);
$this->params['breadcrumbs'][] = ['label' => 'Breeds', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="breed-update container-fluid">
    <div class="card card-outline card-success shadow-sm">
        <div class="card-header">
            <div class="card-tools">
                <?= Html::a('<i class="fas fa-arrow-left"></i> Voltar à Raça', ['view', 'id'=>$model->id], ['class' => 'btn btn-default btn-sm']) ?>
            </div>
        </div>
        <div class="card-body">
            <?= $this->render('_form', [
                'animalTypes' => $animalTypes,
                'model' => $model,
            ]) ?>
        </div>
    </div>
</div>

