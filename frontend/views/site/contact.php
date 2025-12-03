<?php
use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;
use yii\captcha\Captcha;

$this->title = 'Contacte-nos';
$this->params['breadcrumbs'][] = $this->title;
$txtEmail = 'info@petcompanion.pt';
$txtTelefone = '+351 244 875 627';
?>

<div class="site-contact py-5">
    <div class="container">

        <div class="text-center mb-5">
            <h1 class="title-contact display-5 fw-bold mb-0"><?= Html::encode($this->title) ?></h1>
            <p class="text-muted">
                <?=Html::encode('Estamos aqui para esclarecer as suas dúvidas.')?>
            </p>
        </div>

        <div class="row gy-5 align-items-start">

            <div class="col-lg-5">
                <div class="mb-4">
                    <?= Html::img('@web/img/imgContact.png', [
                        'alt' => 'Contact PetPanion',
                        'class' => 'img-fluid img-contact'
                    ]) ?>
                </div>

                <div class="ps-lg-3">
                    <h3 class="title-infos mb-2"><?=Html::encode('Onde estamos')?></h3>

                    <div class="d-flex mb-4 align-items-start">
                        <div class="me-3">
                            <i class="bi bi-geo-alt-fill contact-icon"></i>
                        </div>
                        <div>
                            <h5 class="info-title"><?=Html::encode('Morada')?></h5>
                            <p class="mb-0 info-text"><?= Html::encode('Politécnico de Leiria - ESTG')?></p>
                            <p class="mb-0 info-text"><?= Html::encode('2415-404 Leiria')?></p>
                        </div>
                    </div>

                    <div class="d-flex mb-4 align-items-start">
                        <div class="me-3">
                            <i class="bi bi-telephone-fill contact-icon"></i>
                        </div>
                        <div>
                            <h5 class="info-title"><?= Html::encode('Telefone')?></h5>
                            <p class="mb-0 info-text"><?= Html::encode('+351 244 875 627')?></p>
                        </div>
                    </div>

                    <div class="d-flex mb-4 align-items-start">
                        <div class="me-3">
                            <i class="bi bi-envelope-at-fill contact-icon"></i>
                        </div>
                        <div>
                            <h5 class="info-title"><?= Html::encode('Email')?></h5>
                            <p class="mb-0 info-text"><?= Html::encode('info@petcompanion.pt')?></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-7">
                <div class="card-contacto p-4 p-md-5">
                    <h2 class="mb-2"><?= Html::encode('Envie-nos uma mensagem!')?></h2>
                    <p class="mb-4 text-muted info-text"><?=Html::encode('Preencha o formulário abaixo e responderemos o mais brevemente possível.')?></p>

                    <?php $form = ActiveForm::begin([
                        'id' => 'contact-form',
                        'fieldConfig' => [
                            'template' => "{label}\n{input}\n{hint}\n{error}",
                            'labelOptions' => ['class' => 'form-label fw-bold mt-3 text-secondary', 'style' => "font-family: 'Quicksand', sans-serif;"],
                            'inputOptions' => ['class' => 'form-control form-control-lg bg-light border-0'],
                        ],
                    ]); ?>

                    <div class="row">
                        <div class="col-md-6">
                            <?= $form->field($model, 'name')->textInput(['placeholder' => 'O seu nome'])->label(false)?>
                        </div>
                        <div class="col-md-6">
                            <?= $form->field($model, 'email')->textInput(['placeholder' => 'O seu email'])->label(false)?>
                        </div>
                    </div>

                    <?= $form->field($model, 'subject')->textInput(['placeholder' => 'Assunto'])->label(false)?>

                    <?= $form->field($model, 'body')->textarea(['rows' => 5, 'placeholder' => 'Escreva aqui a sua mensagem...'])->label(false)?>

                    <div class="mt-3">
                        <?= $form->field($model, 'verifyCode')->widget(Captcha::class, [
                            'template' => '<div class="row align-items-center"><div class="col-lg-4">{image}</div><div class="col-lg-8">{input}</div></div>',
                            'options' => [
                                'class' => 'form-control form-control-lg bg-light border-0',
                                'placeholder' => 'Introduza o código da imagem'
                            ]
                        ])->label('Código de Verificação') ?>
                    </div>
                    <div class="form-group mt-4 text-end">
                        <?= Html::submitButton('Enviar Mensagem <i class="bi bi-send-fill ms-2"></i>', [
                            'class' => 'btn btn-primary btn-lg text-white rounded-pill px-5 py-3 shadow-sm',
                            'style' => 'background-color: var(--primary); border: none;',
                            'name' => 'contact-button'
                        ]) ?>
                    </div>

                    <?php ActiveForm::end(); ?>
                </div>
            </div>

        </div>
    </div>
</div>