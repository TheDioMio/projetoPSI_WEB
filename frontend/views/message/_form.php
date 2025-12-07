<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Message $model */
/** @var yii\widgets\ActiveForm $form */

?>

<div class="message-form p-4" style="background: #fff; border-radius: 15px; box-shadow: 0 0 15px rgba(0,0,0,0.08);">

    <?php $form = ActiveForm::begin(); ?>

    <!-- Destinatário (campo visual apenas) -->
    <div class="mb-3">
        <label class="form-label fw-bold">Para:</label>
        <div class="form-control bg-light" style="pointer-events: none;">
            <?= $receiver ? $receiver->name : '(Desconhecido)' ?>
        </div>
    </div>

    <!-- Assunto -->
    <?= $form->field($model, 'subject')
        ->label('Assunto')
        ->textInput([
            'maxlength' => true,
            'class' => 'form-control rounded-pill px-3'
        ]) ?>

    <!-- Texto -->
    <?= $form->field($model, 'text')
        ->label('Mensagem')
        ->textarea([
            'rows' => 5,
            'class' => 'form-control rounded-4 p-3'
        ]) ?>

    <!-- Botão -->
    <div class="form-group mt-4">
        <?= Html::submitButton(
            '<i class="bi bi-envelope-fill me-2"></i>Enviar',
            [
                'class' => 'btn btn-primary',
            ]
        ) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
