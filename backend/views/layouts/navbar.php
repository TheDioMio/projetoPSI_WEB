<?php

use yii\helpers\Html;
use yii\helpers\Url;

?>
<nav class="main-header navbar navbar-expand navbar-white navbar-light shadow-sm">

    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>

        <!--Assim como no view/animal para as fotos, aqui temos novamente o problema dos links entre frontend
            e backend, por isso temos que implementar a mesma coisa: substituir os links manualmente.-->
        <li class="nav-item d-none d-sm-inline-block">
            <?php
            // 1. Pega no endereço base atual
            $backendUrl = Url::base(true);
            $frontendUrl = str_replace('/backend/web', '/frontend/web', $backendUrl);
            ?>
            <?= Html::a(
                '<i class="fas fa-globe text-success mr-2"></i>Ver Site',
                $frontendUrl,
                [
                    'class' => 'nav-link font-weight-bold',
                    'target' => '_blank',
                ]
            );
            ?>
        </li>

        <li class="nav-item d-none d-sm-inline-block px-2">
            <span class="nav-link text-muted">|</span>
        </li>

        <li class="nav-item d-none d-sm-inline-block">
            <a href="<?=Url::home()?>" class="nav-link"><?=Html::encode('Home')?></a>
        </li>

        <li class="nav-item d-none d-sm-inline-block">
            <a href="<?=Url::to(['/animal/index']) ?>" class="nav-link"><?=Html::encode('Animais')?></a>
        </li>

        <li class="nav-item d-none d-sm-inline-block">
            <a href="<?=Url::to(['/user/index']) ?>" class="nav-link"><?=Html::encode('Utilizadores')?></a>
        </li>
    </ul>

    <ul class="navbar-nav ml-auto">

        <li class="nav-item">
            <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                <i class="fas fa-expand-arrows-alt"></i>
            </a>
        </li>

        <li class="nav-item">
            <?= Html::a(
                '<i class="fas fa-sign-out-alt mr-1"></i>Sair',
                ['/site/logout'],
                [
                    'data-method' => 'post',
                    'class' => 'nav-link text-danger font-weight-bold',
                    'title' => 'Terminar Sessão'
                ]
            ) ?>
        </li>
    </ul>
</nav>