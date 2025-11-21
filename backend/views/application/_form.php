<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Application $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="application-form">
    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'status')->textInput() ?>
    <?= $form->field($model, 'description')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'user_id')->textInput() ?>
    <?= $form->field($model, 'animal_id')->textInput() ?>
    <?= $form->field($model, 'type')->textInput() ?>
    <?= $form->field($model, 'created_at')->textInput() ?>
    <?= $form->field($model, 'target_user_id')->textInput() ?>
    <?= $form->field($model, 'data')->textInput() ?>
    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
