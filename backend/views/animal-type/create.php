<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\AnimalType $model */

$this->title = 'Create Animal Type';
$this->params['breadcrumbs'][] = ['label' => 'Animal Types', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="animal-type-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
