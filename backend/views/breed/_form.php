<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Breed $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="breed-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'description')->textInput()->label('Descrição')?>

    <?= $form->field($model, 'animal_type_id')
        ->dropDownList(ArrayHelper::map($animalTypes, 'id', 'description'))
        ->label('Tipo de Animal')?>

    <div class="form-group">
        <?= Html::submitButton('Guardar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
