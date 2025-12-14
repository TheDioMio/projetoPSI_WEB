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
<div class="application-index container-fluid">
    <div class="card card-outline card-primary shadow-sm">
        <div class="card-header">
            <?= Html::a('Candidaturas User Pro', ['index-user-pro'], [
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
                        'label' => 'Dono do Animal',
                        'value' => 'animalOwner.name',
                        'attribute' => 'animal_owner_name',
                    ],
                    [
                        'label' => 'Animal',
                        'value' => 'animal.name',
                        'attribute' => 'animal_name',
                    ],

                    [
                        'class' => ActionColumn::className(),
                        'template' => '{view} {delete}',
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

        </div>
        <div class="card-body p-0">
            
        </div>
    </div>
</div>
