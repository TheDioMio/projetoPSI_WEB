<?php

use common\models\Listing;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var backend\models\ListingSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Gestão de Listagens';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="listing-index container-fluid">
    <div class="card card-outline card-primary shadow-sm">
        <div class="card-header">
            <div class="card-tools float-right">
                <?= Html::a('<i class="fas fa-plus-circle"></i> Criar Listagem', ['create'], ['class' => 'btn btn-success btn-sm']) ?>
            </div>
        </div>
        <div class="card-body p-0">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'tableOptions' => ['class' => 'table table-hover table-striped table-sm'],
                'layout' => "{items}\n{summary}\n{pager}",
                'columns' => [
                    [
                      'label' => 'Animal Listado',
                        'attribute' => 'animal_name',
                        'value' => 'animal.name',
                    ],
                    [
                            'label' => 'Autor da Listagem',
                        'attribute' => 'listing_user',
                        'value' => 'user.name',
                    ],
                    [
                          'label' => 'Descrição',
                        'attribute'=> 'description',
                    ],
                    [
                            'label' => 'Visualizações',
                        'attribute' => 'views',
                    ],
                    [
                            'label' => 'Data de Criação',
                            'attribute' => 'created_at',
                    ],
                    [
                        'class' => ActionColumn::class,
                        'urlCreator' => function ($action, Listing $model, $key, $index, $column) {
                            return Url::toRoute([$action, 'id' => $model->id]);
                        }
                    ],
                ],
            ]); ?>
        </div>
    </div>
</div>