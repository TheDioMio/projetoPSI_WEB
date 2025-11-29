<?php
use yii\helpers\Html;

$this->title = 'Atualizar Tipo de Animal #' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Animal Types', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="animal-type-update">
    <h1><?= Html::encode($this->title) ?></h1>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
