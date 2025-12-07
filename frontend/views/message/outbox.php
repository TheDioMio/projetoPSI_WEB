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
<div class="message-index">

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
