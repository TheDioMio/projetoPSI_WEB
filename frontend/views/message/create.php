<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Message $model */

$this->title = 'Nova Mensagem';
$this->params['breadcrumbs'][] = ['label' => 'Messages', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="message-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'receiver' => $receiver,
    ]) ?>

</div>
