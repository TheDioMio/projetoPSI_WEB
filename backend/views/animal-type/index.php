<?php
use common\models\AnimalType;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var backend\models\AnimalTypeSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */
$this->title = 'Gestão de Tipos de Animal';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="anymal-type-index container-fluid">
    <div class="card card-outline card-primary shadow-sm">
        <div class="card-header">
            <div class="card-tools float-right">
                <?= Html::a('<i class="fas fa-plus-circle"></i> Criar Tipo Animal', ['create'], ['class' => 'btn btn-success btn-sm']) ?>
            </div>
        </div>
        <div class="card-body p-0">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'tableOptions' => ['class' => 'table table-hover table-striped table-sm text-nowrap'],
                'layout' => "{items}\n{summary}\n{pager}",
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    'id',
                    'description',
                    [
                        'class' => ActionColumn::class,
                        'template' => '{view} {delete}',
                        'urlCreator' => function ($action, AnimalType $model, $key, $index, $column) {
                            return Url::toRoute([$action, 'id' => $model->id]);
                        }
                    ],
                ],
            ]); ?>
        </div>
    </div>
</div>