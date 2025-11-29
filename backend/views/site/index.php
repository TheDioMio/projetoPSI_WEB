<?php

use hail812\adminlte\widgets\SmallBox;
use hail812\adminlte\widgets\InfoBox; // Adicionei esta linha para os widgets novos
use yii\helpers\Url;
use yii\web\View;
use yii\helpers\Json;
use yii\bootstrap5\Html;

$this->title = 'Dashboard';
$this->params['breadcrumbs'] = [['label' => $this->title]];
?>
<div class="row">
    <div class="col-lg-2 col-6">
        <?= SmallBox::widget([
            'title' => count($utilizadores),
            'text' => 'Utilizadores',
            'icon' => 'fas fa-user',
            'theme' => 'gradient-success',
            'linkUrl' => Url::to(['user/index']),
        ]) ?>
    </div>

    <div class="col-lg-2 col-6">
        <?= SmallBox::widget([
            'title' => count($animais),
            'text' => 'Animais',
            'icon' => 'fas fa-paw',
            'theme' => 'gradient-success',
            'linkUrl' => Url::to(['animal/index']),
        ]) ?>
    </div>

    <div class="col-lg-2 col-6">
        <?= SmallBox::widget([
            'title' => count($listagens),
            'text' => 'Listagens',
            'icon' => 'fas fa-hand-holding-heart',
            'theme' => 'gradient-success',
            'linkUrl' => Url::to(['application/index']),
        ]) ?>
    </div>

    <div class="col-lg-2 col-6">
        <?= SmallBox::widget([
            'title' => count($candidaturas),
            'text' => 'Candidaturas',
            'icon' => 'fas fa-file-contract',
            'theme' => 'gradient-success',
            'linkUrl' => Url::to(['application/index']),
        ]) ?>
    </div>

</div>