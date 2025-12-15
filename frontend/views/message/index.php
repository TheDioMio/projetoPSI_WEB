<?php

use common\models\Message;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var frontend\models\MessageSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Messages recebidas';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="container py-4">
    <div class="row g-4">
        <div class="message-index col-lg-8">

            <h1><?= Html::encode($this->title) ?></h1>

            <p style="display: flex; justify-content: flex-end;">
                <?= Html::a('Ver mensagens enviadas', ['outbox'], ['class' => 'btn btn-primary']) ?>
            </p>

            <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

            <?= GridView::widget([
                'dataProvider' => $dataProvider,
        //        'filterModel' => $searchModel,
                'rowOptions' => function($model) {
                    $url = \yii\helpers\Url::to(['message/view', 'id' => $model->id, 'type' => 'inbox']);

                    if ($model->isRead == 0) {
                        return [
                            'style' => 'background-color: #f5fbff; font-weight: bold;',
                            'onclick' => "window.location='{$url}';",
                            'class' => 'clickable-row'
                        ];
                    }

                    return [
                        'onclick' => "window.location='{$url}';",
                        'class' => 'clickable-row'
                    ];
                },
                'columns' => [
                    [
                        'label' => '',
                        'format' => 'raw',
                        'value' => function ($model) {
                            return $model->isRead
                                ? '<i class="fa fa-envelope-open"></i>'
                                : '<i class="fa fa-envelope"></i>';
                        },
                        'contentOptions' => ['style' => 'width: 40px; text-align: center;'],
                    ],
                    [
                        'label' => 'Assunto',
                        'attribute' => 'subject',
                    ],
                    [
                        'label' => 'De',
                        'attribute' => 'sender_user_id',
                        'value' => function ($model) {
                            return $model->senderUser ? $model->senderUser->name : '(Desconhecido)';
                        }
                    ],
                    [
                        'label' => 'Data',
                        'attribute' => 'created_at',
                    ],

                    [
                        'class' => ActionColumn::className(),
                        'template' => '{view}',
                        'urlCreator' => function ($action, Message $model, $key, $index, $column) {
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
                            Mensagens Recebidas
                        </h5>

                        <p class="text-muted mb-3">
                            Nesta área pode consultar todas as mensagens que recebeu de outros utilizadores.
                        </p>

                        <p class="text-muted mb-3">
                            Leia com atenção cada mensagem e acompanhe as comunicações relacionadas
                            com candidaturas, anúncios ou outros contactos.
                        </p>

                        <div class="alert alert-primary-subtle mb-0 small">
                            Uma boa comunicação é essencial para criar confiança e facilitar decisões.
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Sidebar End -->


    </div>
</div>