<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\User $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="user-form">
    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput()->label('Nome') ?>

    <?= $form->field($model, 'email')->textInput() ?>

    <?= $form->field($model, 'username')->textInput()?>

    <?= $form->field($model, 'password')->passwordInput()?>

    <div class="form-group">
        <?= Html::submitButton('Guardar', ['class' => 'btn btn-success']) ?>
    </div>
    <?php ActiveForm::end(); ?>

</div>
