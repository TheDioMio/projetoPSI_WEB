<?php
use common\models\AnimalAge;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

$this->title = 'Gestão de Idades';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="animal-age-index container-fluid">
    <div class="card card-outline card-primary shadow-sm">
        <div class="card-header">
            <div class="card-tools float-right">
                <?= Html::a('<i class="fas fa-plus-circle"></i>',
                    ['create'],
                    [
                        'class' => 'btn btn-success',
                        'title' => 'Criar',
                    ])
                ?>
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
                        'label' => 'Descrição',
                        'attribute' => 'description',
                    ],
                    [
                        'template' => '{update} {delete}',
                        'class' => ActionColumn::className(),
                        'urlCreator' => function ($action, AnimalAge $model, $key, $index, $column) {
                            return Url::toRoute([$action, 'id' => $model->id]);
                        }
                    ],
                ],
            ]); ?>
        </div>
    </div>
</div>
