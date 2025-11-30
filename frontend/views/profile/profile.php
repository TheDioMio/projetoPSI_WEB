<?php

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Tabs;
use yii\helpers\Html;


$this->title = 'O Meu Perfil';
?>

<div class="container py-4">
    <div class="row">
        <!-- Coluna esquerda: cartão / menu do utilizador -->
        <div class="col-md-4 mb-3">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <!-- Avatar -->
                    <div class="mb-3">
                        <img src="/img/default-avatar.png"
                             alt="Avatar"
                             class="rounded-circle img-fluid"
                             style="width: 120px; height: 120px; object-fit: cover;">
                    </div>

                    <!-- Nome e role -->
                    <h5 class="card-title mb-0">
                        <?= Html::encode($user->name ?? $user->username) ?>
                    </h5>
                    <p class="text-muted small mb-2">
                        <?= Html::encode($user->role->description ?? 'Utilizador') ?>
                    </p>

                    <hr>

                    <!-- Pequeno resumo / stats -->
                    <div class="d-flex justify-content-around text-center">
                        <div>
                            <div class="fw-bold"><?= Html::encode($user->listingsCount) ?></div>
                            <div class="small text-muted">Anúncios</div>
                        </div>
                        <div>
                            <div class="fw-bold"><?= Html::encode($user->applicationsCount) ?></div>
                            <div class="small text-muted">Candidaturas</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Coluna direita: tabs com conteúdo -->
        <div class="col-md-8">
            <?php
            // Conteúdos de exemplo – depois substituis por ActiveForm, grids, etc.
            $items = [
                [
                    'label' => 'Perfil',
                    'content' => $this->render('_tab_profile', [
                        'user' => $user,
                    ]),
                    'active' => true,
                ],
                [
                    'label' => 'Segurança',
                    'content' => $this->render('_tab_security', [
                        'user' => $user,
                    ]),
                ],
                [
                    'label' => 'Preferências',
                    'content' => $this->render('_tab_preferences', [
                        'user' => $user,
                    ]),
                ],
                [
                    'label' => 'Atividade',
                    'content' => $this->render('_tab_activity', [
                        'user' => $user,
                    ]),
                ],
            ];

            echo Tabs::widget([
                'items' => $items,
                'options' => ['class' => 'mb-3'], // classes da UL
                'encodeLabels' => false,
            ]);
            ?>
        </div>
    </div>
</div>