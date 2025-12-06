<?php
use common\models\Role;
use common\models\User;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;

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
                        'label' => 'Status',
                        'attribute' => 'status',
                        'format' => 'raw', //Tem que ser raw, para os botões serem renderizados (Botões HTML)
                        'value' => function ($model) {
                            if ($model->status == 10) {
                                //Se for ativo (10), mostra o botão verde, e ao clicar desativa
                                return Html::a('Ativo', ['update-status', 'id' => $model->id], [
                                    'class' => 'btn btn-xs btn-success btn-block',
                                    'data' => [
                                        'method' => 'post',
                                    ],
                                ]);
                            } else {
                                //Se for inativo (10), mostra o botão vermelho, e ao clicar ativa
                                return Html::a('Inativo', ['update-status', 'id' => $model->id], [
                                    'class' => 'btn btn-xs btn-secondary btn-danger btn-block',
                                    'data' => [
                                        'method' => 'post',
                                    ],
                                ]);
                            }
                        },
                        'filter' => [
                            10 => 'Ativo',
                            9  => 'Inativo',
                        ],
                        'contentOptions' => ['style' => 'width: 100px; text-align: center;'],
                    ],
                    [
                        'class' => ActionColumn::className(),
                        'urlCreator' => function ($action, User $model) {
                            return Url::toRoute([$action, 'id' => $model->id]);
                        }
                    ],
                ],
            ]); ?>
        </div>
    </div>
</div>