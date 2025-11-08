<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Comment $model */
/** @var yii\widgets\ActiveForm $form */
?>
<div class="comment-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'listing_id')
        ->dropDownList(ArrayHelper::map($listings, 'id', 'animalName'),
            ['prompt'=> 'Selecione o animal' ])
        ->label('Animal Listado')?>

    <?= $form->field($model, 'user_id')
        ->dropDownList(ArrayHelper::map($users, 'id', 'username'),
            ['prompt'=> 'Selecione o autor' ])
        ->label('Autor do Comentário')?>

    <?=$form->field($model, 'text')->textInput()->label('Conteúdo')?>

    <div class="form-group">
        <?= Html::submitButton('Guardar', ['class' => 'btn btn-success']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
