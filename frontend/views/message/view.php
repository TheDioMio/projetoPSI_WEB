<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Message $model */
/** @var string $type */

$isInbox = ($type === 'inbox');

$this->title = $isInbox ? $model->senderUser->name : $model->receiverUser->name;

$this->params['breadcrumbs'][] = [
    'label' => $isInbox ? 'Mensagens recebidas' : 'Mensagens enviadas',
    'url' => $isInbox
        ? ['index', 'type' => 'inbox']
        : ['outbox', 'type' => 'outbox']
];
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="message-view py-5">
    <div class="container">
        
        <div class="row justify-content-center">
            <div class="col-lg-8">

                <div class="border-start border-5 border-primary ps-5 mb-5" style="max-width: 600px">
                    <p class="text-muted">
                        <?= $isInbox ? 'Mensagem enviada por' : 'Mensagem enviada para' ?>
                    </p>
                    <h1 class="display-5 fw-bold"><?= Html::encode($this->title) ?></h1>
                </div>

                <div class="card shadow border-0 p-4 p-md-5">

                    <!-- Data -->
                    <div class="mb-4">
                        <h5 class="text-primary fw-bold mb-1">
                            <i class="bi bi-calendar-event me-2"></i>Data
                        </h5>
                        <p class="mb-0 text-muted">
                            <?= Yii::$app->formatter->asDatetime($model->created_at, 'php:d/m/Y H:i') ?>
                        </p>
                    </div>

                    <!-- Assunto -->
                    <div class="mb-4">
                        <h5 class="text-primary fw-bold mb-1">
                            <i class="bi bi-chat-left-dots-fill me-2"></i>Assunto
                        </h5>
                        <p class="mb-0 fs-5"><?= Html::encode($model->subject) ?></p>
                    </div>

                    <!-- Mensagem -->
                    <div class="mb-4">
                        <h5 class="text-primary fw-bold mb-2">
                            <i class="bi bi-envelope-paper-heart-fill me-2"></i>Mensagem
                        </h5>
                        <div class="bg-light rounded p-3" style="white-space: pre-wrap; font-size: 1.1rem;">
                            <?= Html::encode($model->text) ?>
                        </div>
                    </div>

                    <!-- BOTÕES -->
                    <div class="d-flex justify-content-between mt-4">

                        <!-- Voltar -->
                        <?= Html::a('<i class="bi bi-arrow-left me-2"></i>Voltar',
                            $isInbox
                                ? ['index', 'user_id' => $model->receiver_user_id, 'type' => 'inbox']
                                : ['outbox', 'user_id' => $model->sender_user_id, 'type' => 'outbox'],
                            [
                                'class' => 'btn btn-primary',
                            ]
                        ) ?>

                        <?php if ($isInbox): ?>

                            <!-- Botão Responder -->
                            <?= Html::a('<i class="bi bi-reply-fill me-2"></i>Responder',
                                ['/message/create', 'from' => 'inbox', 'user_id' => $model->sender_user_id,  'listing_id'=>0],
                                [
                                    'class' => 'btn btn-primary',
                                ]
                            ) ?>

                        <?php else: ?>

                            <!-- Botão Nova Mensagem -->
                            <?= Html::a('<i class="bi bi-pencil-square me-2"></i>Nova Mensagem',
                                ['create', 'from' => 'outbox', 'user_id' => $model->receiver_user_id,  'listing_id'=>0],
                                [
                                    'class' => 'btn btn-primary',
                                ]
                            ) ?>

                        <?php endif; ?>

                    </div>

                </div>

            </div>
        </div>
    </div>
</div>
