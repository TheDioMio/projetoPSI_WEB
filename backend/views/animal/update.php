<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Animal $model */

$this->title = 'Atualizar animal ' . mb_strtoupper($model->name);
$this->params['breadcrumbs'][] = ['label' => 'Animals', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="animal-update container-fluid">
    <div class="card card-outline card-success shadow-sm">
        <div class="card-header">
            <div class="card-tools">
                <?= Html::button('<i class="fas fa-arrow-left"></i>', [
                    'class' => 'btn btn-default',
                    'onclick' => 'history.back();',
                ]) ?>
            </div>
        </div>
        <div class="card-body">
            <?= $this->render('_form', [
                'model' => $model,
                'existingImages' => $existingImages,
                'animalTypes' => $animalTypes,
                'breedsByType' => $breedsByType,
                'idades' => $idades,
                'portes' => $portes,
                'vacinas' => $vacinas,
                'users' => $users,
            ]) ?>
        </div>
    </div>
</div>
