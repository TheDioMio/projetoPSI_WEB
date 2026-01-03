<?php

use common\models\Application;
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Application $model */
/** @var string $box */ // inbox | outbox

$isInbox = ($box === 'inbox');

$this->title = $isInbox
    ? 'Candidatura de ' . ($model->candidate->name ?? '')
    : 'Candidatura enviada para ' . ($model->animalOwner->name ?? '');

$this->params['breadcrumbs'][] = [
    'label' => $isInbox ? 'Candidaturas Recebidas' : 'Candidaturas Enviadas',
    'url' => [$isInbox ? 'inbox' : 'outbox'],
];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="application-view py-5">
    <div class="container">

        <div class="row justify-content-center">
            <div class="col-lg-8">

                <div class="border-start border-5 border-primary ps-4 mb-4">
                    <h1 class="h3 fw-bold"><?= Html::encode($this->title) ?></h1>
                </div>

                <div class="card shadow-sm border-0 p-4">

                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-body p-4">

                            <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">

                                <!-- ESQUERDA -->
                                <div>
                                    <h4 class="mb-2">
                                        <?=Html::encode('Candidatura')?>
                                    </h4>

                                    <div class="text-muted small mb-2">
                                        <i class="bi bi-calendar-event me-1"></i>
                                        <?=Html::encode('Submetida em')?>
                                        <?= Yii::$app->formatter->asDatetime($model->created_at, 'php:d/m/Y H:i') ?>
                                    </div>

                                    <div class="text-muted small">
                                        <i class="bi bi-heart me-1"></i>
                                        <?=Html::encode('Animal:')?>
                                        <strong><?= Html::encode($model->animal->name ?? '—') ?></strong>
                                    </div>
                                </div>


                                <div class="text-end">
                                    <div class="mb-1 text-muted small">
                                        <?=Html::encode('Estado atual')?>
                                    </div>

                                    <?php
                                    $statusClass = match ($model->status) {
                                        Application::STATUS_APPROVED  => 'success',
                                        Application::STATUS_REJECTED  => 'danger',
                                        Application::STATUS_IN_REVIEW => 'warning',
                                        Application::STATUS_CANCELLED => 'secondary',
                                        Application::STATUS_SENT => 'warning',
                                        default                       => 'primary',
                                    };
                                    ?>

                                    <span class="badge bg-<?= $statusClass ?> fs-6 px-3 py-2">
                    <?= Html::encode($model->getStatusLabel()) ?>
                </span>

                                    <?php if (!empty($model->statusDate)): ?>
                                        <div class="small text-muted mt-1">
                                            desde <?= Yii::$app->formatter->asDate($model->statusDate, 'php:d/m/Y') ?>
                                        </div>
                                    <?php endif; ?>
                                </div>

                            </div>

                        </div>
                    </div>






                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-body p-4">

                            <!-- DADOS PESSOAIS -->
                            <h5 class="text-uppercase text-primary mb-3">
                                <i class="bi bi-person-fill me-2"></i> Dados Pessoais
                            </h5>

                            <div class="row mb-4">
                                <div class="col-md-4">
                                    <div class="fw-semibold text-muted small">Nome</div>
                                    <div><?= Html::encode($model->data['name'] ?? '—') ?></div>
                                </div>
                                <div class="col-md-4">
                                    <div class="fw-semibold text-muted small">Idade</div>
                                    <div><?= Html::encode($model->data['age'] ?? '—') ?></div>
                                </div>
                                <div class="col-md-4">
                                    <div class="fw-semibold text-muted small">Contacto</div>
                                    <div><?= Html::encode($model->data['contact'] ?? '—') ?></div>
                                </div>
                            </div>

                            <hr>

                            <!-- HABITAÇÃO -->
                            <h5 class="text-uppercase text-primary mb-3">
                                <i class="bi bi-house-fill me-2"></i> Habitação
                            </h5>

                            <div class="row mb-4">
                                <div class="col-md-4">
                                    <div class="fw-semibold text-muted small">Tipo de habitação</div>
                                    <div><?= Html::encode($model->getHomeLabel()) ?></div>
                                </div>
                                <div class="col-md-4">
                                    <div class="fw-semibold text-muted small">Tempo sozinho</div>
                                    <div><?= Html::encode($model->getTimeAloneLabel()) ?></div>
                                </div>
                                <div class="col-md-4">
                                    <div class="fw-semibold text-muted small">Crianças em casa</div>
                                    <div>
                    <span class="badge <?= ($model->data['children'] ?? null) ? 'bg-success' : 'bg-danger' ?>">
                        <?= Html::encode($model->getYesNoLabel($model->data['children'] ?? null)) ?>
                    </span>
                                    </div>
                                </div>
                            </div>

                            <hr>

                            <!-- RESPONSABILIDADE -->
                            <h5 class="text-uppercase text-primary mb-3">
                                <i class="bi bi-shield-check me-2"></i> Responsabilidade
                            </h5>

                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <div class="fw-semibold text-muted small">Ciente dos custos</div>
                                    <div>
                    <span class="badge <?= ($model->data['bills'] ?? null) ? 'bg-success' : 'bg-danger' ?>">
                        <?= Html::encode($model->getYesNoLabel($model->data['bills'] ?? null)) ?>
                    </span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="fw-semibold text-muted small">Aceita acompanhamento</div>
                                    <div>
                    <span class="badge <?= ($model->data['followUp'] ?? null) ? 'bg-success' : 'bg-danger' ?>">
                        <?= Html::encode($model->getYesNoLabel($model->data['followUp'] ?? null)) ?>
                    </span>
                                    </div>
                                </div>
                            </div>

                            <hr>

                            <!-- MOTIVAÇÃO -->
                            <h5 class="text-uppercase text-primary mb-3">
                                <i class="bi bi-chat-left-text-fill me-2"></i> Motivação
                            </h5>

                            <div class="bg-light rounded p-3">
                                <?= nl2br(Html::encode($model->data['motive'] ?? '—')) ?>
                            </div>

                        </div>
                    </div>






                    <!-- BOTÕES -->
                    <div class="d-flex justify-content-between mt-4">

                        <?= Html::a('Voltar',
                            [$box === 'inbox' ? 'inbox' : 'outbox'],
                            ['class' => 'btn btn-outline-secondary']
                        ) ?>

                        <?php
                        $userId = Yii::$app->user->id;
                        $isOwner = ($userId == $model->target_user_id); // Sou o dono do animal?
                        $isCandidate = ($userId == $model->user_id);    // Sou quem se candidatou?
                        ?>

                        <?php if ($isOwner && ($model->status === Application::STATUS_SENT || $model->status === Application::STATUS_IN_REVIEW)): ?>                            <div>
                                <?= Html::a(
                                    '<i class="bi bi-x-circle"></i> Rejeitar',
                                    ['application/reject', 'id' => $model->id],
                                    [
                                        'class' => 'btn btn-danger me-2',
                                        'data' => [
                                            'confirm' => 'Tem a certeza que quer rejeitar esta candidatura?',
                                            'method' => 'post',
                                        ],
                                    ]
                                ) ?>

                                <?= Html::a(
                                    '<i class="bi bi-check-circle"></i> Aprovar',
                                    ['application/approve', 'id' => $model->id],
                                    [
                                        'class' => 'btn btn-success',
                                        'data' => [
                                            'confirm' => 'Tem a certeza que quer aprovar esta candidatura?',
                                            'method' => 'post',
                                        ],
                                    ]
                                ) ?>
                            </div>

                        <?php elseif ($isCandidate && ($model->status === Application::STATUS_SENT || $model->status === Application::STATUS_IN_REVIEW)): ?>

                            <?= Html::a(
                                '<i class="bi bi-slash-circle"></i> Cancelar candidatura',
                                ['application/cancel', 'id' => $model->id],
                                [
                                    'class' => 'btn btn-warning',
                                    'data' => [
                                        'confirm' => 'Tem a certeza que quer cancelar esta candidatura?',
                                        'method' => 'post',
                                    ],
                                ]
                            ) ?>

                        <?php endif; ?>

                    </div>

                </div>

            </div>
        </div>

    </div>
</div>


























<?php
//
//use yii\helpers\Html;
//use yii\widgets\DetailView;
//
///** @var yii\web\View $this */
///** @var common\models\Application $model */
//
//$this->title = $model->id;
//$this->params['breadcrumbs'][] = ['label' => 'Applications', 'url' => ['index']];
//$this->params['breadcrumbs'][] = $this->title;
//\yii\web\YiiAsset::register($this);
//?>
<!--<div class="application-view">-->
<!---->
<!--    <h1>--><?php //= Html::encode($this->title) ?><!--</h1>-->
<!---->
<!--    <p>-->
<!--        --><?php //= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
<!--        --><?php //= Html::a('Delete', ['delete', 'id' => $model->id], [
//            'class' => 'btn btn-danger',
//            'data' => [
//                'confirm' => 'Are you sure you want to delete this item?',
//                'method' => 'post',
//            ],
//        ]) ?>
<!--    </p>-->
<!---->
<!--    --><?php //= DetailView::widget([
//        'model' => $model,
//        'attributes' => [
//            'id',
//            'status',
//            'description',
//            'user_id',
//            'animal_id',
//            'type',
//            'created_at',
//            'target_user_id',
//            'data',
//        ],
//    ]) ?>
<!---->
<!--</div>-->
