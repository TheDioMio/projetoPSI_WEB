<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this
 * @var \common\models\Animal $animal
 * @var \common\models\Application $model*/



$this->title = 'Candidatura';


// Opçoes para dropdown


$home = [
    1 => 'Própria',
    2 => 'Arrendada (Senhorio autoriza animais)',
    3 => 'Arrendada (Senhorio não autoriza animais)',
];


$vacinas = [
    0 => 'Não Vacinado',
    1 => 'Vacinado (Básicas)',
    2 => 'Vacinado (Completo)',
    3 => 'Não Aplicável / Desconhecido',
];

//$posterUrl = Yii::getAlias('@web/img/adopt_me.jpg');

?>

<div class="container py-5">
    <h1 class="text-uppercase border-start border-5 border-primary ps-3 mb-4">
        <?= Html::encode($this->title) ?>
    </h1>

    <p>Está prestes a candidatar-se para a adoção do(a) <?= Html::encode($animal->name) ?>.</p>

</div>

<div class="bg-light rounded p-4 p-sm-5">

    <p> <h3>Obrigado pelo seu interesse! </h3>
    O preenchimento deste formulário é o primeiro passo para a adoção.</p>

    <p> Por favor, seja o mais honesto e detalhado possível.
        As suas respostas ajudam-nos a perceber se este é o animal certo para si e para a sua família.</p>

    <br />



<?php $form = ActiveForm::begin([
    'id' => 'apply-form',
    'action' => ['/site/apply', 'animal_id' => $animal->id],
    'method' => 'post',
]); ?>

    <?= $form->field($model, 'data[name]')->textInput(['maxlength' => true])->label('Nome Completo') ?>
    <?= $form->field($model, 'data[age]')->textInput(['type' => 'number'])->label('Idade') ?>
    <?= $form->field($model, 'data[contact]')->textInput(['type' => 'number'])->label('Contacto') ?>
    <?= $form->field($model, 'data[motive]')->textarea(['rows' => 6]) ->label('O que o motivou a adotar um animal?') ?>





    <div class="form-group">
        <?= Html::submitButton('Submeter Candidatura', ['class' => 'btn btn-primary w-100 py-3 mt-5 text-uppercase']) ?>
    </div>



<?php $form = ActiveForm::end()?>

</div>
