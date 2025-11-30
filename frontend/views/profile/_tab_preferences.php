<?php
use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
?>

<div class="card mb-3">
    <div class="card-header">
        <strong>Preferências</strong>
    </div>
    <div class="card-body">
        <?php $form = ActiveForm::begin(); ?>

<!--        --><?php //= $form->field($user, 'language')->dropDownList([
//            'pt-PT' => 'Português',
//            'en-US' => 'Inglês',
//        ]) ?>

<!--        --><?php //= $form->field($user, 'notifications_email')->checkbox()->label('Receber notificações por email') ?>
<!--        --><?php //= $form->field($user, 'notifications_sms')->checkbox()->label('Receber notificações por SMS') ?>

        <div class="mt-3">
            <?= Html::submitButton('Guardar preferências', ['class' => 'btn btn-primary']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>
