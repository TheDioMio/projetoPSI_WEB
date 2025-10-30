<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var \frontend\models\ContactForm $model */

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;

$this->title = 'Formulário de Contacto';
$this->params['breadcrumbs'][] = $this->title;
$txtLocalizacao = 'Politécnico de Leiria - ESTG';
$txtZipcode = '2415-404 Leiria';
$txtEmail = 'info@petcompanion.pt';
$txtTelefone = '+351 244 875 627';
?>

<div class="site-contact container mt-5 mb-5">
    <div class="row contact-panel">
        <div class="col-lg-6 form-panel">
            <h2><?=Html::encode($this->title)?></h2>

            <?php $form = ActiveForm::begin([
                'id' => 'contact-form',
                'fieldConfig' => [
                    'template' => "{label}\n{input}\n{hint}\n{error}",
                    'labelOptions' => ['class' => 'form-label text-white-50 mt-3'],
                    'inputOptions' => ['class' => 'form-control'],
                ],
            ]); ?>

            <?= $form->field($model, 'name')->textInput(['placeholder' => 'Nome Completo'])->label(false) ?>
            <?= $form->field($model, 'email')->textInput(['placeholder' => 'Email'])->label(false) ?>
            <?= $form->field($model, 'subject')->textInput(['placeholder' => 'Assunto'])->label(false) ?>
            <?= $form->field($model, 'body')->textarea(['rows' => 4, 'placeholder' => 'Escreva aqui a sua mensagem...'])->label(false) ?>

            <div class="form-group mt-5">
                <?= Html::submitButton('Enviar Mensagem', ['class' => 'btn btn-light btn-lg text-primary fw-bold', 'name' => 'contact-button']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>

        <div class="col-lg-6 info-panel">
            <h2 class="mb-0"><?=Html::encode('Contacte-nos')?></h2>
            <p class="mt-0 mb-5"><?=Html::encode('Estamos sempre disponíveis.')?></p>

            <div class="info-item">
                <i class="bi bi-house-fill"></i>
                <div>
                    <small><?=Html::encode('Morada')?></small>
                    <p class="mb-0"><?=Html::encode($txtLocalizacao)?><br><?=Html::encode($txtZipcode)?></p>
                </div>
            </div>

            <div class="info-item">
                <i class="bi bi-telephone-fill"></i>
                <div>
                    <small><?=Html::encode('Telefone')?></small>
                    <p class="mb-0"><?=Html::encode($txtTelefone)?></p>
                </div>
            </div>

            <div class="info-item">
                <i class="bi bi-envelope-at-fill"></i>
                <div>
                    <small><?=Html::encode('Email')?></small>
                    <p class="mb-0"><?=Html::encode($txtEmail)?></p>
                </div>
            </div>
        </div>
    </div>
</div>
