<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Listing $model */

$this->title = 'Atualizar Listagem #'. $listingModel->id;
$this->params['breadcrumbs'][] = ['label' => 'Listings', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $listingModel->id, 'url' => ['view', 'id' => $listingModel->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="listing-update container-fluid">
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
                'listingModel' => $listingModel,
                'animalModel' => $animalModel,
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
