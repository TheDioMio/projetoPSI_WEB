<?php
/** @var \yii\web\View $this */
/** @var \common\models\User $user */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
?>

<div class="card mb-3">
    <div class="card-header">
        <strong>Dados do perfil</strong>
    </div>
    <div class="card-body">
        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($user, 'name')->textInput()->label('Nome') ?>
        <?= $form->field($user, 'email')->input('email') ?>
        <?= $form->field($user, 'address')->textInput() ->label('Morada') ?>
<!--        --><?php //= $form->field($user, 'phone')->textInput() ?>
<!--        --><?php //= $form->field($user, 'bio')->textarea(['rows' => 3]) ?>

        <div class="mt-3">
            <?= Html::submitButton('Guardar', ['class' => 'btn btn-primary']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>

