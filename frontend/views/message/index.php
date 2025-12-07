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
<div class="message-index">

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
