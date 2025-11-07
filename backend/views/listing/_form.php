<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Listing $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="listing-form">

    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'animal_id')
        ->dropDownList(ArrayHelper::map($animals, 'id', 'name'),
            ['prompt'=> 'Selecione o animal' ])
        ->label('Animal Listado')?>

    <?= $form->field($model, 'user_id')
        ->dropDownList(ArrayHelper::map($users, 'id', 'username'),
            ['prompt'=> 'Selecione o autor' ])
        ->label('Autor do Comentário')?>

    <?= $form->field($model, 'description')->textInput()->label('Descrição')?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
