<?php

use common\models\Application;
use yii\helpers\Html;
//use yii\widgets\ActiveForm;
use yii\bootstrap5\ActiveForm;

$this->title = 'Candidatura';

//$posterUrl = Yii::getAlias('@web/img/adopt_me.jpg');
?>

<!-- <img src="<?= Yii::getAlias('@web/img/adopt_me.jpg') ?>" alt="Adote-me"> -->

<div class="container py-5">
    <h1 class="text-uppercase border-start border-5 border-primary ps-3 mb-4">
        <?= Html::encode($this->title) ?>
    </h1>
    <p><?= Html::encode('Está prestes a candidatar-se para a adoção do(a) ' . $animal->name) ?>.</p>
</div>

<!-- LAYOUT (form + sidebar) -->
<div class="container">
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="bg-light rounded p-4 p-sm-5">
                <h3><?=Html::encode('Obrigado pelo seu interesse!')?></h3>
                <p><?=Html::encode('O preenchimento deste formulário é o primeiro passo para a adoção.')?></p>
                <p><?=Html::encode('Por favor, seja o mais honesto e detalhado possível.
                    As suas respostas ajudam-nos a perceber se este é o animal certo para si e para a sua família.')?></p>
                <?php $form = ActiveForm::begin([
                    'id' => 'apply-form',
                    'action' => ['/application/apply', 'animal_id' => $animal->id],
                    'method' => 'post',
                ]); ?>

                <?= $form->errorSummary($model, [
                    'class' => 'alert alert-danger',
                    'header' => '<strong>Há erros no formulário:</strong>',
                ]) ?>

                <h4 class="mt-4 mb-3"><?=Html::encode('Dados Pessoais')?></h4>

                <?= $form->field($model, 'data[name]')->textInput(['maxlength' => true])->label('Nome Completo') ?>

                <?= $form->field($model, 'data[age]')->textInput(['type' => 'number'])->label('Idade') ?>

                <?= $form->field($model, 'data[contact]')->textInput(['type' => 'tel'])->label('Contacto') ?>

                <hr class="my-4">
                <h4 class="mt-4 mb-3"><?=Html::encode('Habitação')?></h4>

                <?= $form->field($model, 'data[home]')
                    ->dropDownList(Application::homeOptions(), ['prompt' => 'Selecione o tipo de habitação...'])
                    ->label('Tipo de Habitação') ?>

                <?= $form->field($model, 'data[timeAlone]')
                    ->dropDownList(Application::timeAloneOptions(), ['prompt' => 'Selecione a sua resposta...'])
                    ->label('Quantas horas o animal vai passar sozinho?') ?>

                <?= $form->field($model, 'data[children]')
                    ->radioList(Application::yesNoOptions(), [
                        'item' => function($i,$label,$name,$checked,$value){
                            $id = $name.$i;
                            return '<input type="radio" class="btn-check" name="'.$name.'" id="'.$id.'" value="'.$value.'" '.($checked?'checked':'').'>
                            <label class="btn btn-outline-primary me-2 mb-2" for="'.$id.'">'.$label.'</label>';
                        }
                    ])->label('Tem crianças em casa? Foram instruídas para os cuidados com o animal?') ?>

                <hr class="my-4">

                <h4 class="mt-4 mb-3"><?=Html::encode('Custos de um animal')?></h4>

                <?= $form->field($model, 'data[bills]')
                    ->radioList(Application::yesNoOptions(), [
                        'item' => function($i,$label,$name,$checked,$value){
                            $id = $name.$i;
                            return '<input type="radio" class="btn-check" name="'.$name.'" id="'.$id.'" value="'.$value.'" '.($checked?'checked':'').'>
                            <label class="btn btn-outline-primary me-2 mb-2" for="'.$id.'">'.$label.'</label>';
                        }
                    ])->label('Está ciente dos custos? (Comida, Veterinário, etc.)')
                    ->hint('Inclui alimentação, vacinas, desparasitação, imprevistos') ?>

                <hr class="my-4">

                <h4 class="mt-4 mb-3"><?=Html::encode('Acompanhamento')?></h4>

                <?= $form->field($model, 'data[followUp]')
                    ->radioList(Application::yesNoOptions(), [
                        'item' => function($i,$label,$name,$checked,$value){
                            $id = $name.$i;
                            return '<input type="radio" class="btn-check" name="'.$name.'" id="'.$id.'" value="'.$value.'" '.($checked?'checked':'').'>
                            <label class="btn btn-outline-primary me-2 mb-2" for="'.$id.'">'.$label.'</label>';
                        }
                    ])->label('Aceita visita de acompanhamento pós-adoção?') ?>

                <hr class="my-4">
                <h4 class="mt-4 mb-3"><?=Html::encode('Conte-nos sobre si')?></h4>
                <?= $form->field($model, 'data[motive]')
                    ->textarea(['rows' => 6])
                    ->label('O que o motivou a adotar um animal?') ?>
                <div class="form-group">
                    <?= Html::submitButton('Submeter Candidatura', ['class' => 'btn btn-primary w-100 py-3 mt-5 text-uppercase']) ?>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>

        <!-- RIGHT: SIDEBAR -->
        <div class="col-lg-4">
            <div class="position-sticky" style="top:2rem;">
                <div class="card shadow-sm border-0 mb-3">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div class="flex-grow-1">
                                <h2 class="h6 mb-0"><?= Html::encode($animal->name) ?></h2>
                                <small class="text-muted"><?=Html::encode('Resumo do animal')?></small>
                            </div>
                            <span class="badge bg-success-subtle text-success"><?=Html::encode('Disponível')?></span>
                        </div>

                        <?php if (!empty($animal->photo_url)): ?>
                            <img src="<?= Html::encode($animal->photo_url) ?>" class="img-fluid rounded mb-3" alt="<?= Html::encode($animal->name) ?>">
                        <?php endif; ?>

                        <ul class="list-unstyled small mb-0">
                            <li class="mb-2"><span class="text-muted"><?=Html::encode('Raça:')?></span> <strong><?= Html::encode($animal->breed->description ?? '—') ?></strong></li>
                            <li class="mb-2"><span class="text-muted"><?=Html::encode('Idade:')?></span> <strong><?= Html::encode($animal->age->description ?? '—') ?></strong></li>
                            <li class="mb-2"><span class="text-muted"><?=Html::encode('Porte:')?></span> <strong><?= Html::encode($animal->size->description ?? '—') ?></strong></li>
                        </ul>
                    </div>
                </div>

                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h3 class="h6 mb-3"><?=Html::encode('Dicas para uma boa candidatura')?></h3>
                        <ul class="small text-muted mb-0">
                            <li><?=Html::encode('Explique a sua rotina e tempo disponível.')?></li>
                            <li><?=Html::encode('Conte-nos sobre a casa e regras do senhorio.')?></li>
                            <li><?=Html::encode('Mostre que está preparado para custos e imprevistos.')?></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

