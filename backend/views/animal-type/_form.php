<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>
<div class="animal-type-form">
    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'description')->textInput(['maxlength' => true])->label('Descrição') ?>
    <div class="form-group">
        <?= Html::submitButton('Guardar', ['class' => 'btn btn-success']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
