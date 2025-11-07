<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Listing $model */

$this->title = 'Update Listing: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Listings', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="listing-update container-fluid">
    <div class="card card-outline card-success shadow-sm">
        <div class="card-header">
            <div class="card-tools">
                <?= Html::a('<i class="fas fa-arrow-left"></i> Voltar à Listagem', ['view', 'id'=>$model->id], ['class' => 'btn btn-default btn-sm']) ?>
            </div>
        </div>
        <div class="card-body">
            <?= $this->render('_form', [
                'model' => $model,
                'animals' => $animals,
                'users' => $users,
            ]) ?>
        </div>
    </div>
</div>
