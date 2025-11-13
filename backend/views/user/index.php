<?php

use common\models\Role;
use common\models\User;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var \common\models\UserSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */
$this->title = 'Gestão de Utilizadores';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index container-fluid">
    <div class="card card-outline card-primary shadow-sm">
        <div class="card-header">
            <div class="card-tools float-right">
                <?= Html::a('<i class="fas fa-plus-circle"></i> Criar Utilizador', ['create'], ['class' => 'btn btn-success btn-sm']) ?>
            </div>
        </div>
        <div class="card-body p-0">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'tableOptions' => ['class' => 'table table-hover table-striped table-sm'],
                'layout' => "{items}\n{summary}\n{pager}",
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    [
                        'label' => 'ID',
                        'attribute' => 'id',
                    ],
                    'username',
                    'email:email',
                    [
                        'label' => 'Permissões',
                        'attribute' => 'role_description',
                        'value' => 'role.description',
                    ],
                    [
                            'label'=> 'Status',
                        'attribute'=> 'status',
                        'value'=> function ($model) {
                            if($model->status == 10){
                                return 'Ativo';
                            } else if ($model->status == 9){
                                return 'Inativo';
                            } else {
                                return 'Desconhecido';
                            }
                        },
                        'filter' => [
                            10 => 'Ativo',
                            9  => 'Inativo',
                        ],
                    ],
                    [
                        'class' => ActionColumn::className(),
                        'urlCreator' => function ($action, User $model, $key, $index, $column) {
                            return Url::toRoute([$action, 'id' => $model->id]);
                        }
                    ],
                ],
            ]); ?>
        </div>
    </div>
</div>