<?php

use hail812\adminlte3\assets\AdminLteAsset;
use yii\helpers\Html;
use yii\web\YiiAsset;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\Animal $model */

$this->title = 'Detalhes do Animal ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Animais', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

YiiAsset::register($this);
?>
<div class="animal-view fade-in-up">
    <div class="d-flex justify-content-between align-items-center mb-4 ">
        <div>
            <h1 class="display-6 font-weight-bold text-primary">
                <i class="fas fa-paw me-2"></i><?= ' '.Html::encode($model->name) ?>
            </h1>
            <p class="text-muted mb-0"><?='ID do Registo: #'. $model->id ?></p>
        </div>
        <div>
            <?= Html::a('<i class="fas fa-arrow-left"></i>',
                ['index'],
                [
                    'class' => 'btn btn-outline-secondary mr-1',
                    'title' => 'Voltar',
                ],
            )
            ?>
            <?= Html::a('<i class="fas fa-edit"></i>',
                ['update', 'id' => $model->id],
                [
                        'class' => 'btn btn-primary mr-1',
                        'title' => 'Editar',
                ],
            )
            ?>
            <?= Html::a('<i class="fas fa-trash"></i>',
                ['delete', 'id' => $model->id],
                [
                'class' => 'btn btn-danger',
                'title' => 'Apagar',
                'data' => [
                    'confirm' => 'Tem a certeza que deseja apagar este animal?',
                    'method' => 'post',
                ],
            ]) ?>
        </div>
    </div>

    <div class="row align-items-stretch">
        <div class="col-lg-5 mb-4">
            <div class="card shadow border-0 overflow-hidden h-100">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-images mr-2"></i><?=Html::encode('Fotos')?></h5>
                </div>
                <div class="card-body p-0 d-flex align-items-center bg-light justify-content-center" style="min-height: 400px;">
                    <?php if ($totalImages > 0): ?>
                        <div id="animalCarousel" class="carousel slide w-100" data-ride="carousel">
                            <ol class="carousel-indicators">
                                <?= $carouselIndicators ?>
                            </ol>

                            <div class="carousel-inner">
                                <?= $carouselItems ?>
                            </div>

                            <?php if ($totalImages > 1): ?>
                                <a class="carousel-control-prev" href="#animalCarousel" role="button" data-slide="prev">
                                    <span class="carousel-control-prev-icon bg-dark rounded-circle p-2" aria-hidden="true"></span>
                                    <span class="sr-only"><?=Html::encode('Anterior')?></span>
                                </a>
                                <a class="carousel-control-next" href="#animalCarousel" role="button" data-slide="next">
                                    <span class="carousel-control-next-icon bg-dark rounded-circle p-2" aria-hidden="true"></span>
                                    <span class="sr-only"><?=Html::encode('Próximo')?></span>
                                </a>
                            <?php endif; ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center text-muted p-5">
                            <i class="fas fa-camera fa-4x mb-3 text-secondary opacity-50"></i>
                            <p><?=Html::encode('Sem fotografias disponíveis')?></p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-lg-7 mb-4">
            <div class="card shadow-sm border-0 h-100 d-flex flex-column">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-info-circle mr-2"></i><?=Html::encode('Ficha Técnica')?></h5>
                </div>
                <div class="card-body p-0 h-100">
                    <?= DetailView::widget([
                        'model' => $model,
                        'options' => ['class' => 'table table-hover table-striped mb-0 h-100 table-layout-fixed'],
                        'formatter' => [
                            'class' => 'yii\i18n\Formatter',
                            'nullDisplay' => '<span class="text-muted text-italic">Sem registo</span>',
                        ],
                        'attributes' => [
                            [
                                'label' => 'Nome do Animal',
                                'attribute' => 'name',
                                'contentOptions' => ['class' => 'font-weight-bold text-dark lead align-middle'],
                                'captionOptions' => ['class' => 'align-middle'],
                            ],
                            [
                                'label' => 'Dono',
                                'format' => 'raw', //Raw porque o nome do dono é um botão (a)
                                'value' => function ($model) {
                                    if (!$model->user) {
                                        return '<span class="text-muted">(Sem dono associado)</span>';
                                    }
                                    return Html::a(
                                        $model->user->username, //Texto que aparece no botão (nome do dono)
                                        ['user/view', 'id' => $model->user->id], //A rota para onde vai (backend/user/view)
                                        [
                                            'target' => '_blank',
                                        ]
                                    );
                                },
                                'contentOptions' => ['class' => 'align-middle'],
                                'captionOptions' => ['class' => 'align-middle'],
                            ],
                            [
                                'label' => 'Raça',
                                'attribute' => 'breed.description',
                                'contentOptions' => ['class' => 'align-middle'],
                                'captionOptions' => ['class' => 'align-middle'],
                            ],
                            [
                                'label' => 'Porte',
                                'attribute' => 'size.description',
                                'contentOptions' => ['class' => 'align-middle'],
                                'captionOptions' => ['class' => 'align-middle'],
                            ],
                            [
                                'label' => 'Idade',
                                'attribute' => 'animalAge.description',
                                'contentOptions' => ['class' => 'align-middle'],
                                'captionOptions' => ['class' => 'align-middle'],
                            ],
                            [
                                'label' => 'Vacinação',
                                'attribute' => 'vaccination.description',
                                'contentOptions' => ['class' => 'align-middle'],
                                'captionOptions' => ['class' => 'align-middle'],
                            ],
                            [
                                'label' => 'Sobre o Animal',
                                'attribute' => 'description',
                                'format' => 'ntext',
                                'contentOptions' => ['class' => 'text-secondary p-3 align-middle'],
                                'captionOptions' => ['class' => 'align-middle'],
                            ],
                        ],
                    ]) ?>
                </div>
            </div>
        </div>
    </div>
</div>