<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\AnimalSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="animal-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'description') ?>

    <?= $form->field($model, 'size') ?>

    <?= $form->field($model, 'age') ?>

    <?= $form->field($model, 'animal_type_id') ?>

    <?php // echo $form->field($model, 'breed_id') ?>

    <?php // echo $form->field($model, 'vaccines') ?>

    <?php // echo $form->field($model, 'neutered') ?>

    <?php // echo $form->field($model, 'location') ?>

    <?php // echo $form->field($model, 'user_id') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
