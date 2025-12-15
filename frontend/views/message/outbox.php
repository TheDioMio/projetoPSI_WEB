<?php

use common\models\Message;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var frontend\models\MessageSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Messages enviadas';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="container py-4">
    <div class="row g-4">
        <div class="message-index col-lg-8">

            <h1><?= Html::encode($this->title) ?></h1>

            <p style="display: flex; justify-content: flex-end;">
                <?= Html::a('Ver mensagens Recebidas', ['index'], ['class' => 'btn btn-primary']) ?>
            </p>

            <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

            <?= GridView::widget([
                'dataProvider' => $dataProvider,
        //        'filterModel' => $searchModel,
                'rowOptions' => function ($model) {
                    return [
                        'style' => '',
                        'onclick' => 'window.location="' . Url::to(['message/view', 'id' => $model->id, 'type' => 'outbox']) . '";',
                        'onmouseover' => 'this.style.cursor="pointer";',
                    ];
                },
                'columns' => [
                    [
                        'label' => 'Assunto',
                        'attribute' => 'subject',
                    ],

                    [
                        'label' => 'Para',
                        'attribute' => 'receiver_user_id',
                        'value' => function ($model) {
                            return $model->receiverUser ? $model->receiverUser->name : '(Desconhecido)';
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
                            return Url::toRoute([$action, 'id' => $model->id, 'type' => "outbox"]);
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
                            <span class="me-2">📤</span>
                            Mensagens Enviadas
                        </h5>

                        <p class="text-muted mb-3">
                            Aqui pode rever todas as mensagens que enviou.
                        </p>

                        <p class="text-muted mb-3">
                            Utilize este espaço para acompanhar respostas,
                            confirmar informações enviadas e manter o histórico das suas comunicações.
                        </p>

                        <div class="alert alert-primary-subtle mb-0 small">
                            Manter uma comunicação clara ajuda a tornar todo o processo mais simples.
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Sidebar End -->

    </div>
</div>

