<?php
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>
<div class="user-form">
    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput()->label('Nome') ?>

    <?= $form->field($model, 'email')->textInput() ?>

    <?= $form->field($model, 'username')->textInput()?>

    <?= $form->field($model, 'password')->passwordInput()?>

    <?= $form->field($model, 'status')->dropDownList([10 => 'Ativo', 9  => 'Inativo'],)?>

    <?= $form->field($model, 'role_id')
        ->dropDownList(ArrayHelper::map($roles, 'id', 'description'),
            ['prompt'=> 'Selecione o role' ])
        ->label('Role do Utilizador')?>

    <div class="form-group">
        <?= Html::submitButton('Guardar', ['class' => 'btn btn-success']) ?>
    </div>
    <?php ActiveForm::end(); ?>

</div>
