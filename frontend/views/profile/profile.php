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
                    <div class="mb-3 text-center">

                        <?php
                        // pegar avatar do utilizador
                        $avatar = Yii::$app->request->baseUrl . '/img/default-avatar.png';

                        if ($user->profileImage) {
                            $avatar = Yii::$app->request->baseUrl . '/' . ltrim($user->profileImage->path, '/');
                        }
                        ?>

                        <!-- Preview da foto -->
                        <img id="avatar-preview"
                             src="<?= $avatar ?>"
                             class="rounded-circle img-fluid mb-2"
                             style="width: 120px; height:120px; object-fit:cover">

                        <!-- Formulário de upload -->
                        <?php $form = \yii\widgets\ActiveForm::begin([
                            'action' => ['profile/upload-image'],
                            'options' => ['enctype' => 'multipart/form-data']
                        ]); ?>

<!--                        --><?php //= $form->field($user, 'imageFile')->fileInput([
//                            'accept' => 'image/*',
//                            'onchange' => 'previewAvatar(event)'
//                        ])->label(false) ?>
                        <label class="btn btn-outline-secondary btn-sm">
                            Selecionar imagem
                            <?= $form->field($user, 'imageFile')->fileInput([
                                'accept' => 'image/*',
                                'onchange' => 'previewAvatar(event)',
                                'style' => 'display:none'
                            ])->label(false) ?>
                        </label>

                        <button class="btn btn-primary btn-sm">Guardar imagem</button>

                        <?php \yii\widgets\ActiveForm::end(); ?>
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

<script>
    function previewAvatar(event) {
        const file = event.target.files[0];

        if (!file) return;

        const reader = new FileReader();

        reader.onload = function(e) {
            document.getElementById('avatar-preview').src = e.target.result;
        };

        reader.readAsDataURL(file);
    }
</script>
