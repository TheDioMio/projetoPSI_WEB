<?php

use common\models\Application;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\ApplicationSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Gestão de Candidaturas';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="application-index-user-pro container-fluid">
    <div class="card card-outline card-primary shadow-sm">
        <div class="card-header">
            <?= Html::a('Candidaturas para Adoção', ['index'], [
                    'class' => 'btn btn-danger btn-sm',])
            ?>
        </div>
        <div class="card-body p-0">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'tableOptions' => ['class' => 'table table-hover table-striped table-sm'],
                'layout' => "{items}\n{summary}\n{pager}",
                'columns' => [
                    [
                        'label' => 'Candidato',
                        'value' => 'candidate.name',
                        'attribute' => 'candidate_name',
                    ],
                    [
                        'attribute' => 'description',
                        'label' => 'Empresa',
                    ],
                    [
                        'attribute' => 'created_at',
                        'format' => ['date', 'php:d/m/Y H:i'],
                        'label' => 'Submetido em',
                    ],
                    [
                        'attribute' => 'status',
                        'label' => 'Estado',
                        'format' => 'raw',
                        'filter' => [
                            Application::STATUS_APPROVED => 'Aprovado',
                            Application::STATUS_REJECTED => 'Negado',
                        ],
                        'value' => function ($model) {
                            // 1. Pedimos ao Controller a informação deste status específico
                            // O 'Yii::$app->controller' refere-se ao ApplicationController que está a correr
                            $info = Yii::$app->controller->getStatusInfo($model->status);

                            // 2. Usamos os dados que vieram do Controller
                            return Html::tag('span', $info['label'], [
                                'class' => "badge {$info['class']} px-2"
                            ]);
                        },
                        'contentOptions' => ['class' => 'text-center align-middle'],
                    ],
                    [
                        'class' => ActionColumn::className(),
                        'template' => '{view} {delete}',
                        'buttons' => [
                            'view' => function ($url, $model) {
                                $novoUrl = Url::to(['view-user-pro', 'id' => $model->id]);
                                return Html::a('<i class="fas fa-eye"></i>', $novoUrl);
                            },
                        ],
                        'urlCreator' => function ($action, Application $model, $key, $index, $column) {
                            return Url::toRoute([$action, 'id' => $model->id]);
                        }
                    ],
                ],
            ]); ?>
        </div>
    </div>

    <div class="card card-outline card-warning shadow-sm">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-briefcase mr-1"></i>
                <?=Html::encode('Candidaturas Profissionais por Aceitar')?>
            </h3>
            <div class="card-tools">
                <span class="badge badge-warning"><?= $pendingUserProApplications->getTotalCount() ?> <?=Html::encode('Pendentes')?></span>
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                </button>
            </div>
        </div>
        <div class="card-body p-0">
            <?= GridView::widget([
                'dataProvider' => $pendingUserProApplications,
                'layout' => "{items}\n{pager}",
                'tableOptions' => ['class' => 'table table-valign-middle table-hover'],
                'columns' => [
                    [
                        'label' => 'Candidato',
                        'value' => 'candidate.name',
                        'attribute' => 'candidate_name',
                    ],
                    [
                        'attribute' => 'description',
                        'label' => 'Empresa',
                    ],
                    [
                        'attribute' => 'created_at',
                        'format' => ['date', 'php:d/m/Y H:i'],
                        'label' => 'Submetido em',
                    ],
                    [
                        'class' => ActionColumn::className(),
                        'template' => '{view}', //Isto é só para ver, depois o sysadmin abre a candidatura e dá para aprovar lá dentro
                        'contentOptions' => ['class' => 'text-right'],
                        'buttons' => [
                            'view' => function ($url, $model) {
                                $novoUrl = Url::to(['view-user-pro', 'id' => $model->id]);
                                return Html::a('<i class="fas fa-eye"></i> Analisar', $novoUrl, [
                                    'class' => 'btn btn-xs btn-primary',
                                ]);
                            },
                        ],
                        'urlCreator' => function ($action, Application $model) {
                            return Url::toRoute([$action, 'id' => $model->id]);
                        }
                    ],
                ],
            ]); ?>
        </div>
    </div>
</div>
