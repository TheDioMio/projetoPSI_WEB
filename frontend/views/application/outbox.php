
<?php

use common\models\Application;
use yii\grid\ActionColumn;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Candidaturas Enviadas';
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="application-outbox">

    <h1><?= Html::encode($this->title) ?></h1>

    <p class="text-end">
        <?= Html::a('Ver candidaturas recebidas', ['inbox'], ['class' => 'btn btn-primary']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'rowOptions' => function ($model) {
            $url = Url::to(['application/view', 'id' => $model->id, 'box' => 'outbox']);
            return [
                'onclick' => "window.location='{$url}'",
                'class' => 'clickable-row'
            ];
        },
        'columns' => [
            [
                'label' => 'Destinatário',
                'value' => fn($model) => $model->animalOwner->name ?? '—'
            ],
            [
                'label' => 'Animal',
                'value' => fn($model) => $model->animal->name ?? '—'
            ],
            [
                'label' => 'Estado',
                'value' => fn($model) => $model->getStatusLabel()
            ],
            [
                'attribute' => 'created_at',
                'label' => 'Data'
            ],
            [
                'class' => ActionColumn::className(),
                'template' => '{view}',
                'urlCreator' => function ($action, Application $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id, 'type' => "outbox"]);
                }
            ],
        ],
    ]); ?>

</div>
