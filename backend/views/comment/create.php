<?php

use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var common\models\Comment $model */

$this->title = 'Criar Comentário';
$this->params['breadcrumbs'][] = ['label' => 'Gestão de Comentários', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="comment-create container-fluid">
    <div class="card card-outline card-success shadow-sm">
        <div class="card-header">
            <div class="card-tools">
                <?= Html::a('<i class="fas fa-arrow-left"></i> Voltar à Lista', ['index'], ['class' => 'btn btn-default btn-sm']) ?>
            </div>
        </div>
        <div class="card-body">
            <?= $this->render('_form', [
                'model' => $model,
                'users' => $users,
                'listings' => $listings,
            ]) ?>
        </div>
    </div>
</div>