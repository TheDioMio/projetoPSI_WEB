<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Animal $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="animal-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'animal_type_id')
        ->dropDownList(ArrayHelper::map($animalTypes, 'id', 'description'),
            ['prompt'=> '--> Selecione o tipo <--' ])
        ->label('Tipo')?>

    <?= $form->field($model, 'breed_id')
        ->dropDownList(ArrayHelper::map($breeds, 'id', 'description'),
            ['prompt'=> '--> Selecione a raça <--' ])
        ->label('Raça')?>

    <?= $form->field($model, 'name')->textInput()->label('Nome') ?>

    <?= $form->field($model, 'user_id')
        ->dropDownList(ArrayHelper::map($users, 'id', 'name'),
            ['prompt'=> '--> Selecione o dono <--' ])
        ->label('Dono')?>

    <?= $form->field($model, 'age_id')
        ->dropDownList(ArrayHelper::map($ages, 'id', 'description'),
            ['prompt'=> '--> Selecione a idade <--'])
        ->label('Idade')?>

    <?= $form->field($model, 'size_id')
        ->dropDownList(ArrayHelper::map($sizes, 'id', 'description'),
            ['prompt'=> '--> Selecione o tamanho <--' ])
        ->label('Tamanho')?>

    <?= $form->field($model, 'vaccination_id')
        ->dropDownList(ArrayHelper::map($vaccines, 'id', 'description'),
            ['prompt'=> '--> Selecione o estado da vacinação <--'])
        ->label('Vacinação')?>
    
    <?= $form->field($model, 'neutered')->dropDownList([0 => 'Não', 1 => 'Sim'])->label('Castrado') ?>

    <?= $form->field($model, 'location')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6])->label('Descrição') ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
