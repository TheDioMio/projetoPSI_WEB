<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\AnimalAge $model */

$this->title = 'Create Animal Age';
$this->params['breadcrumbs'][] = ['label' => 'Animal Ages', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="animal-age-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
