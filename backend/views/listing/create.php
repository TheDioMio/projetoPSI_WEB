<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Listing $listingModel */
/** @var common\models\Animal $animalModel */

$this->title = 'Criar Anúncio e Animal';
$this->params['breadcrumbs'][] = ['label' => 'Listings', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="listing-create container-fluid">
    <div class="card card-outline card-success shadow-sm">
        <div class="card-header">
            <h3 class="card-title"><?= Html::encode($this->title) ?></h3>
            <div class="card-tools">
                <?= Html::a('<i class="fas fa-arrow-left"></i> Voltar à Lista', ['index'], ['class' => 'btn btn-default btn-sm']) ?>
            </div>
        </div>
        <div class="card-body">

            <?= $this->render('_form', [
                'listingModel' => $listingModel,
                'animalModel' => $animalModel,
                'users' => $users,
                'animalTypes' => $animalTypes,
                'breedsByType' => $breedsByType,
                'idades' => $idades,
                'portes' => $portes,
                'vacinas' => $vacinas,
            ]) ?>

        </div>
    </div>
</div>