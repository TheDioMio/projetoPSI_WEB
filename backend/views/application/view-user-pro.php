<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\YiiAsset;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\Application $model */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Applications', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
YiiAsset::register($this);
?>
<div class="application-view-user-pro">
        <div class="mb-3 text-right">
                <?= Html::a('<i class="fas fa-arrow-left"></i> Voltar à Lista', ['index-user-pro'],
                    ['class' => 'btn btn-default']) ?>
                <?= Html::a('Negar', ['deny-application', 'id' => $model->id], [
                    'class' => 'btn btn-danger',
                    'data' => [
                        'confirm' => 'Tem a certeza que deseja negar esta candidatura?',
                        'method' => 'post',
                    ],
                ]) ?>
                <?= Html::a('Aceitar', ['accept-application', 'id' => $model->id], [
                    'class' => 'btn btn-success',
                    'data' => [
                        'confirm' => 'Tem a certeza que deseja aceitar esta candidatura?',
                        'method' => 'post',
                    ],
                ]) ?>
        </div>


        <div class="row">
            <div class="col-md-4">
                <div class="card card-primary card-outline shadow-sm">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-id-card"></i> Resumo</h3>
                    </div>
                    <div class="card-body box-profile">
                        <div class="text-center mb-3">
                        <span class="img-circle elevation-2 d-inline-flex align-items-center justify-content-center bg-light" style="width: 80px; height: 80px; font-size: 2rem;">
                            <i class="fas fa-user text-secondary"></i>
                        </span>
                        </div>

                        <h3 class="profile-username text-center">
                            <?= Html::a($model->user->username ?? 'Desconhecido',
                                ['user/view', 'id' => $model->user_id],
                                ['target'=> '_blank'])?></h3>
                        <p class="text-muted text-center">Candidato</p>

                        <ul class="list-group list-group-unbordered mb-3">
                            <li class="list-group-item">
                                <b>ID da Candidatura</b> <a class="float-right">#<?= $model->id ?></a>
                            </li>
                            <li class="list-group-item">
                                <b>Data de Submissão</b> <a class="float-right"><?= Yii::$app->formatter->asDate($model->created_at, 'long') ?></a>
                            </li>
                            <li class="list-group-item">
                                <b>Status</b>
                                <span class="float-right">
                                <?=Html::tag('span', $statusLabel, ['class' => "badge {$statusClass}"]);
                                ?>
                            </span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card card-info card-outline shadow-sm">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-list-alt"></i> Respostas do Formulário</h3>
                    </div>
                    <div class="card-body p-0">
                        <?php if (empty($jsonAttributes)): ?>
                            <div class="p-4 text-center text-muted">Sem dados para apresentar.</div>
                        <?php else: ?>

                            <?= DetailView::widget([
                                'model' => $model,
                                'options' => ['class' => 'table table-hover mb-0'],
                                'attributes' => ArrayHelper::merge(
                                    [
                                        [
                                            'attribute' => 'description',
                                            'label' => 'Empresa',
                                            'captionOptions' => ['class' => 'text-muted'],
                                            'contentOptions' => ['class' => 'font-weight-bold'],
                                        ],
                                    ],
                                    $jsonAttributes
                                ),
                            ]) ?>

                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
</div>