<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var \common\models\LoginForm $model */

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;
use yii\helpers\Url; // Certifica-te que está presente

$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="site-login d-flex justify-content-center align-items-center w-100 py-5">
    <div class="container-img-login img-login-esq d-none d-md-block">
        <?= Html::img('@web/img/imgFundoLoginFrontend_ESQ.png', [
            'alt' => 'Imagem fundo lado esquerdo',
            'class' => 'img-fluid login-image-asset'
        ]) ?>
    </div>
    <div class="form-login-centrar">
        <div>
            <h1 class="text-center"><?= Html::encode($this->title) ?></h1>
            <p class="text-center">Bem-vindo de volta!</p>
        </div>
        <div class="row">
            <div class="col-12">
                <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>

                <?= $form->field($model, 'username')->textInput(['autofocus' => true, 'placeholder' => 'Utilizador'])->label(false)?>
                <?= $form->field($model, 'password')->passwordInput(['placeholder' => 'Password'])->label(false)?>
                <?= $form->field($model, 'rememberMe')->checkbox()->label('Lembrar Login') ?>

                <div class="my-1 mx-0" style="color:#999;">
                    Esqueçeste-te da password? <?= Html::a('Recuperar', ['site/request-password-reset']) ?>.
                </div>

                <div class="form-group mt-5">
                    <?= Html::submitButton('Login', ['class' => 'btn btn-primary w-100', 'name' => 'login-button']) ?>
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
                <div class="d-block">Conexão Segura</div>
            </div>

            <div class="col-4">
                <div class="d-block">Suporte 24/7</div>
            </div>

            <div class="col-4">
                <div class="d-block">Privacidade é Prioridade</div>
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