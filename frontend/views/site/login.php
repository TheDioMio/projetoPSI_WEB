<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var \common\models\LoginForm $model */

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;
use yii\helpers\Url; // Certifica-te que está presente

$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;

// Definimos estilos para a imagem para garantir que a altura seja a mesma do formulário central
$imgOptions = [
    'alt' => 'Imagem de fundo',
    'class' => 'img-fluid',
    'style' => 'height: 600px; width: 250px; object-fit: cover;' // Ajusta width/height conforme necessário
];
?>

<div class="site-login d-flex justify-content-center align-items-center w-100 py-5">

    <div class="d-none d-md-block">
        <?= Html::img('@web/img/imgFundoLoginFrontend_ESQ.png', $imgOptions) ?>
    </div>

    <div class="login-form-center" style="max-width: 400px; padding: 20px;">

        <h1 class="text-center"><?= Html::encode($this->title) ?></h1>

        <p class="text-center">Bem-vindo de volta!</p>

        <div class="row">
            <div class="col-12">
                <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>

                <?= $form->field($model, 'username')->textInput(['autofocus' => true, 'placeholder' => 'Utilizador'])->label(false)?>

                <?= $form->field($model, 'password')->passwordInput(['placeholder' => 'Password'])->label(false)?>

                <?= $form->field($model, 'rememberMe')->checkbox()->label('Lembrar Login') ?>

                <div class="my-1 mx-0" style="color:#999;">
                    Esqueçeste-te da password? <?= Html::a('Recupera-a', ['site/request-password-reset']) ?>.
                </div>

                <div class="form-group">
                    <?= Html::submitButton('Login', ['class' => 'btn btn-primary w-100', 'name' => 'login-button']) ?>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
    <div class="login-image-right d-none d-md-block">
        <?= Html::img('@web/img/imgFundoLoginFrontend_DIR.png', $imgOptions) ?>
    </div>

</div>