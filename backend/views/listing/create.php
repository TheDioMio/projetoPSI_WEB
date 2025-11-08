<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Listing $model */

$this->title = 'Criar uma Listagem';
$this->params['breadcrumbs'][] = ['label' => 'Listings', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="listing-create container-fluid">
    <div class="card card-outline card-success shadow-sm">
        <div class="card-header">
            <div class="card-tools">
                <?= Html::a('<i class="fas fa-arrow-left"></i> Voltar à Lista', ['index'], ['class' => 'btn btn-default btn-sm']) ?>
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