<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Animal $model */

$this->title = 'Criar Animal';
$this->params['breadcrumbs'][] = ['label' => 'Animals', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="animal-create container-fluid">
    <div class="card card-outline card-success shadow-sm">
        <div class="card-header">
            <div class="card-tools">
                <?= Html::a('<i class="fas fa-arrow-left"></i> Voltar à Lista', ['index'], ['class' => 'btn btn-default btn-sm']) ?>
            </div>
        </div>
        <div class="card-body">
            <?= $this->render('_form', [
                'model' => $model,
                'sizes' => $sizes,
                'breeds' => $breeds,
                'animalTypes' => $animalTypes,
                'users' => $users,
                'vaccines' => $vaccines,
                'ages' => $ages,
            ]) ?>
        </div>
    </div>
</div>