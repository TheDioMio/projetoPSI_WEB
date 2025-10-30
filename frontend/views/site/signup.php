<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var \frontend\models\SignupForm $model */

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;

$this->title = 'Criar Conta';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="site-signup d-flex justify-content-center align-items-center w-100 py-5">
    <div class="container-img-login img-login-esq d-none d-md-block">
        <?= Html::img('@web/img/imgFundoLoginFrontend_ESQ.png', [
            'alt' => 'Imagem fundo lado esquerdo',
            'class' => 'img-fluid login-image-asset'
        ]) ?>
    </div>

    <div class="form-login-centrar">
        <div>
            <h1 class="text-center"><?= Html::encode($this->title) ?></h1>
            <p class="text-center"><?=Html::encode('Seja bem-vindo à PetPanion!')?></p>
        </div>

        <div class="row">
            <div class="col-12">
                <?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>
                <?= $form->field($model, 'username', ['options' => ['class' => 'form-bg mb-2']])
                    ->textInput(['placeholder' => 'Utilizador'])
                    ->label(false) ?>
                <?= $form->field($model, 'email', ['options' => ['class' => 'form-bg mb-2']])
                    ->textInput(['placeholder' => 'Email'])
                    ->label(false) ?>
                <?= $form->field($model, 'password', ['options' => ['class' => 'form-bg mb-2']])
                    ->passwordInput(['placeholder' => 'Password'])
                    ->label(false) ?>

                <div class="info-adds my-1 mx-0">
                    <p><?=Html::encode('Esqueceu-se da password?')?> <?=Html::a('Recuperar', ['site/request-password-reset']) ?>.<br>
                    <?=Html::encode('Já tem conta?')?> <?= Html::a('Login', ['site/login']) ?>.</p>
                </div>

                <div class="form-group mt-5">
                    <?= Html::submitButton('Criar Conta', ['class' => 'btn btn-primary w-100', 'name' => 'signup-button']) ?>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>

        <div>
            <?= Html::tag('hr', '', ['class' => 'mt-2 mb-1']) ?>
            <?= Html::tag('hr', '', ['class' => 'mt-1 mb-3']) ?>
        </div>

        <div class="row text-center">
            <div class="col-4">
                <div class="d-block"><?= Html::encode('Registo Seguro')?></div>
            </div>
            <div class="col-4">
                <div class="d-block"><?= Html::encode('Suporte 24/7')?></div>
            </div>
            <div class="col-4">
                <div class="d-block"><?= Html::encode('Privacidade Garantida')?></div>
            </div>
        </div>
    </div>

    <div class="container-img-login img-login-drt d-none d-md-block">
        <?= Html::img('@web/img/imgFundoLoginFrontend_DIR.png', [
            'alt' => 'Imagem fundo lado direito',
            'class' => 'img-fluid login-image-asset'
        ]) ?>
    </div>
</div>
