<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Vaccination $model */

$this->title = 'Create Vaccination';
$this->params['breadcrumbs'][] = ['label' => 'Vaccinations', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="vaccination-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
