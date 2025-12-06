<?php
use common\models\Breed;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

$this->title = 'Gestão de Raças';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="breed-index container-fluid">
    <div class="card card-outline card-primary shadow-sm">
        <div class="card-header">
            <div class="card-tools float-right">
                <?= Html::a('<i class="fas fa-plus-circle"></i> Criar Raça', ['create'], ['class' => 'btn btn-success btn-sm']) ?>
            </div>
        </div>
        <div class="card-body p-0">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'tableOptions' => ['class' => 'table table-hover table-striped table-sm text-nowrap'],
                'layout' => "{items}\n{summary}\n{pager}",
                'columns' => [
                    'id',
                    [
                        'label' => 'Raça',
                        'attribute' => 'description',
                    ],
                    [
                        'label' => 'Tipo de Animal',
                        'attribute' => 'animal_type_name',
                        'value' => 'animalType.description',
                    ],
                    [
                        'class' => ActionColumn::class,
                        'urlCreator' => function ($action, Breed $model, $key, $index, $column) {
                            return Url::toRoute([$action, 'id' => $model->id]);
                        }
                    ],
                ],
            ]); ?>
        </div>
    </div>
</div>
