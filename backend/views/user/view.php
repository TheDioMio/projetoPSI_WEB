<?php

use yii\helpers\Html;
use yii\web\YiiAsset;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\User $model */

$this->title = 'Perfil de ' . $model->username;
$this->params['breadcrumbs'][] = ['label' => 'Utilizadores', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
YiiAsset::register($this);
?>

<div class="user-view fade-in-up">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="display-6 font-weight-bold text-primary">
                <i class="fas fa-user-circle me-2"></i><?= ' '.Html::encode($model->username) ?>
            </h1>
            <p class="text-muted mb-0">
                <?= 'Registo #'. $model->id ?> |
                <span class="badge <?= $model->status == 10 ? 'bg-success' : 'bg-danger' ?>">
                    <?= $model->status == 10 ? 'Ativo' : 'Inativo' ?>
                </span>
            </p>
        </div>
        <div>
            <?= Html::a('<i class="fas fa-arrow-left"></i> Voltar', ['index'], ['class' => 'btn btn-outline-secondary mr-1']) ?>
            <?= Html::a('<i class="fas fa-edit"></i> Editar', ['update', 'id' => $model->id], ['class' => 'btn btn-primary mr-1']) ?>
            <?= Html::a('<i class="fas fa-trash"></i> Apagar', ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => 'Tem a certeza que deseja eliminar este utilizador?',
                    'method' => 'post',
                ],
            ]) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8 mb-4">
            <div class="card shadow border-0 overflow-hidden">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-id-card mr-2"></i><?=Html::encode('Dados da Conta')?></h5>
                </div>
                <div class="card-body p-0">
                    <?= DetailView::widget([
                        'model' => $model,
                        'options' => ['class' => 'table table-hover table-striped mb-0 table-layout-fixed'],
                        'formatter' => [
                            'class' => 'yii\i18n\Formatter',
                            'nullDisplay' => '<span class="text-muted">Não definido</span>',
                        ],
                        'attributes' => [
                            [
                                'label' => 'Nome Completo',
                                'attribute' => 'name',
                                'contentOptions' => ['class' => 'font-weight-bold text-dark align-middle'],
                                'value' => function($model) {
                                    if($model->name == null)
                                        return null;
                                    else{
                                        return ($model->name);
                                    }
                                }
                            ],
                            [
                                'label' => 'Username',
                                'attribute' => 'username',
                                'contentOptions' => ['class' => 'align-middle'],
                                'captionOptions' => ['class' => 'align-middle'],
                                'value' => function($model) {
                                    if($model->username == null)
                                        return null;
                                    else{
                                        return ($model->username);
                                    }
                                }
                            ],
                            [
                                'label' => 'Email',
                                'attribute' => 'email',
                                'format' => 'email',
                                'contentOptions' => ['class' => 'text-primary align-middle'],
                                'value' => function($model) {
                                    if($model->email == null)
                                        return null;
                                    else{
                                        return ($model->email);
                                    }
                                }
                            ],
                            [
                                'label' => 'Morada',
                                'attribute' => 'address',
                                'contentOptions' => ['class' => 'align-middle'],
                                'value' => function($model) {
                                    if($model->address == null)
                                        return null;
                                    else{
                                        return ($model->address);
                                    }
                                }
                            ],
                            [
                                'label' => 'Permissões',
                                'attribute' => 'role.description',
                                'format' => 'text',
                                'contentOptions' => ['class' => 'align-middle'],
                                'value' => function($model) {
                                    if($model->role->description == null)
                                        return null;
                                    else{
                                        return ($model->role->description);
                                    }
                                }
                            ],
                            [
                                'label' => 'Data de Registo',
                                'attribute' => 'created_at',
                                'format' => ['datetime', 'php:d/m/Y H:i'],
                                'contentOptions' => ['class' => 'align-middle'],
                                'value' => function($model) {
                                    if($model->created_at == null)
                                        return null;
                                    else{
                                        return ($model->created_at);
                                    }
                                }
                            ],
                        ],
                    ]) ?>
                </div>
            </div>
        </div>

        <div class="col-lg-4 mb-4">
            <div class="card shadow-sm border-0 mb-4 text-center">
                <div class="card-body p-5">
                    <div class="mb-3">
                        <div class="mb-3 d-flex justify-content-center">
                            <?php if ($avatar == null): ?>
                                <span class="fa-stack fa-4x">
                                    <i class="fas fa-circle fa-stack-2x text-light"></i>
                                    <i class="fas fa-user fa-stack-1x text-secondary"></i>
                                </span>
                            <?php else: ?>
                            <img src="<?= $avatar ?>"
                                 class="img-circle elevation-2 user-image"
                                 alt="User Image"
                            >
                            <?php endif; ?>
                        </div>
                    </div>
                    <h4 class="font-weight-bold mb-1"><?= Html::encode($model->name)?></h4>
                    <p class="text-muted"><?= Html::encode($model->email)?></p>

                    <hr>

                    <div class="d-flex justify-content-around text-center mt-3">
                        <div>
                            <h5 class="font-weight-bold mb-0 text-primary">
                                <?=$totalUserAnimais?>
                            </h5>
                            <small class="text-uppercase text-muted"><?=Html::encode('Animais')?></small>
                        </div>
                        <div>
                            <h5 class="font-weight-bold mb-0 text-success">
                                <?=$totalUserApplications?>
                            </h5>
                            <small class="text-uppercase text-muted"><?=Html::encode('Candidaturas')?></small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>