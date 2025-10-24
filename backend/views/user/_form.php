<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\User $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="user-form">


    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'password')->passwordInput() ?>

<!--    <div class="form-group field-user-password">-->
<!--        <label class="control-label" for="user-password">Password</label>-->
<!--        <input type="password" id="user-password" class="form-control" name="User[password]">-->
<!--        <div class="help-block"></div>-->
<!--    </div>-->



    <!--?= $form->field($model, 'password')->passwordInput() ?-->
<!--    --><?php //= $form->field($model, 'auth_key')->textInput(['maxlength' => true]) ?>
<!---->
<!--    --><?php //= $form->field($model, 'password_hash')->textInput(['maxlength' => true]) ?>
<!---->
<!--    --><?php //= $form->field($model, 'address')->textInput(['maxlength' => true]) ?>
<!---->
<!--    --><?php //= $form->field($model, 'password_reset_token')->textInput(['maxlength' => true]) ?>
<!---->
<!--    --><?php //= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>
<!---->
<!--    --><?php //= $form->field($model, 'status')->textInput() ?>
<!---->
<!--    --><?php //= $form->field($model, 'created_at')->textInput() ?>
<!---->
<!--    --><?php //= $form->field($model, 'updated_at')->textInput() ?>
<!---->
<!--    --><?php //= $form->field($model, 'verification_token')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
