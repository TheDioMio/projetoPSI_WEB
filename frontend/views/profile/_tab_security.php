<?php
use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
?>

<div class="card mb-3">
    <div class="card-header">
        <strong>Segurança</strong>
    </div>
    <div class="card-body">
        <?php $form = ActiveForm::begin(); ?>

<!--        --><?php //= $form->field($user, 'currentPassword')->passwordInput()->label('Password atual') ?>
<!--        --><?php //= $form->field($user, 'newPassword')->passwordInput()->label('Nova password') ?>
<!--        --><?php //= $form->field($user, 'newPasswordRepeat')->passwordInput()->label('Repetir nova password') ?>

        <div class="mt-3">
            <?= Html::submitButton('Atualizar password', ['class' => 'btn btn-warning']) ?>
        </div>

        <?php ActiveForm::end(); ?>

        <hr>

        <p class="text-danger small mb-1">Zona perigosa</p>
        <?= Html::a('Eliminar conta', ['profile/delete-account'], [
            'class' => 'btn btn-outline-danger btn-sm',
            'data' => [
                'confirm' => 'Tens a certeza que queres eliminar a conta?',
                'method' => 'post',
            ],
        ]) ?>
    </div>
</div>
