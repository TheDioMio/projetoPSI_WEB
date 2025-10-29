<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Breed $model */

$this->title = 'Create Breed';
$this->params['breadcrumbs'][] = ['label' => 'Breeds', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="breed-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'animalTypes' => $animalTypes,
        'model' => $model,
    ]) ?>

</div>
