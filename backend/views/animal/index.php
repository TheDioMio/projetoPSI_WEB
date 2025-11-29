<?php

use common\models\Animal;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var backend\models\AnimalSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Gestão de Animais';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="animal-index container-fluid">
    <div class="card card-outline card-primary shadow-sm">
<!--        <div class="card-header">-->
<!--            <div class="card-tools float-right">-->
<!--                --><?php //= Html::a('<i class="fas fa-plus-circle"></i> Criar Animal', ['create'], ['class' => 'btn btn-success btn-sm']) ?>
<!--            </div>-->
<!--        </div>-->
        <div class="card-body p-0">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'tableOptions' => ['class' => 'table table-hover table-striped table-sm'],
                'layout' => "{items}\n{summary}\n{pager}",
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    'id',
                    [
                        'label'=>'Nome',
                        'attribute'=>'name',
                    ],
                    [
                        'label'=>'Tipo de Animal',
                        'attribute'=>'animal_type',
                        'value'=>'animalType.description',
                    ],
                    [
                        'label'=>'Idade',
                        'attribute'=>'animal_age',
                        'value'=>'animalAge.description',
                    ],
                    [
                        'class' => ActionColumn::className(),
                        'urlCreator' => function ($action, Animal $model, $key, $index, $column) {
                            return Url::toRoute([$action, 'id' => $model->id]);
                        }
                    ],
                ],
            ]); ?>
        </div>
    </div>
</div>
