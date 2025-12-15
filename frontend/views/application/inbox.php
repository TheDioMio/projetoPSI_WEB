<?php

use common\models\Application;
use yii\grid\ActionColumn;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var frontend\models\ApplicationSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */


$this->title = 'Candidaturas Recebidas';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="container py-4">
    <div class="row g-4">
        <div class="application-inbox col-lg-8">

            <h1><?= Html::encode($this->title) ?></h1>

            <p class="text-end">
                <?= Html::a('Ver candidaturas enviadas', ['outbox'], ['class' => 'btn btn-primary']) ?>
            </p>

            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'rowOptions' => function ($model) {
                    $url = Url::to(['application/view', 'id' => $model->id, 'box' => 'inbox']);

                    if ((int)$model->isRead === 0) {
                        return [
                            'style' => 'background-color:#f5fbff;font-weight:bold;',
                            'onclick' => "window.location='{$url}'",
                            'class' => 'clickable-row'
                        ];
                    }

                    return [
                        'onclick' => "window.location='{$url}'",
                        'class' => 'clickable-row'
                    ];
                },
                'columns' => [
                    [
                        'label' => '',
                        'format' => 'raw',
                        'value' => fn($model) =>
                        $model->isRead
                            ? '<i class="fa fa-folder-open"></i>'
                            : '<i class="fa fa-folder"></i>',
                        'contentOptions' => ['style' => 'width:40px;text-align:center'],
                    ],
                    [
                        'label' => 'Candidato',
                        'value' => fn($model) => $model->candidate->name ?? '—',
                    ],
                    [
                        'label' => 'Animal',
                        'value' => fn($model) => $model->animal->name ?? '—',
                    ],
                    [
                        'label' => 'Estado',
                        'value' => fn($model) => $model->getStatusLabel(),
                    ],
                    [
                        'label' => 'Data',
                        'attribute' => 'created_at',
                    ],
                    [
                        'class' => ActionColumn::className(),
                        'template' => '{view}',
                        'urlCreator' => function ($action, Application $model, $key, $index, $column) {
                            return Url::toRoute([$action, 'id' => $model->id, 'type' => "inbox"]);
                        }
                    ],
                ],
            ]); ?>
        </div>


        <!-- Sidebar Start -->
        <div class="col-lg-4">
            <div class="position-sticky" style="top: 2rem;">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-body">
                        <h5 class="card-title mb-3 d-flex align-items-center">
                            <span class="me-2">📥</span>
                            Candidaturas Recebidas
                        </h5>

                        <p class="text-muted mb-3">
                            Aqui encontra todas as candidaturas submetidas para os seus animais.
                        </p>

                        <p class="text-muted mb-3">
                            Analise com atenção cada pedido, consulte as informações fornecidas
                            pelos candidatos e acompanhe o estado de cada candidatura.
                        </p>

                        <div class="alert alert-primary-subtle mb-0 small">
                            A sua decisão pode mudar uma vida — obrigado por dedicar tempo
                            a encontrar o lar certo.
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Sidebar End -->


    </div>
</div>













