<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\AnimalAge $model */

$this->title = 'Update Animal Age: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Animal Ages', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="animal-age-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
