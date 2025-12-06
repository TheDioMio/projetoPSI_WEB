<?php

use yii\helpers\Html;
use yii\web\YiiAsset;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\Animal $model */

$this->title = 'Detalhes do Animal ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Animais', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

YiiAsset::register($this);

// --- LÓGICA DE IMAGENS ---
$images = $model->files;
$totalImages = count($images);
$carouselIndicators = '';
$carouselItems = '';
$i = 0;

if ($totalImages > 0) {
    foreach ($images as $image) {
        $isActive = ($i === 0) ? 'active' : '';

        // Como as imagens são guardadas no caminho de frontend, temos que substituir para o backend:
        $rawUrl = $image->url;
        $imageUrl = str_replace('/backend/web', '/frontend/web', $rawUrl);

        if (strpos($imageUrl, 'http') === false && substr($imageUrl, 0, 1) !== '/') {
            $imageUrl = '/' . $imageUrl;
        }

        $carouselIndicators .= '<li data-target="#animalCarousel" data-slide-to="' . $i . '" class="' . $isActive . '"></li>';
        $carouselItems .= '<div class="carousel-item ' . $isActive . '">';
        $carouselItems .= Html::img($imageUrl, [
            'class' => 'd-block w-100',
            'alt' => $model->name,
            'style' => 'height: 400px; object-fit: cover; width: 100%;'
        ]);
        $carouselItems .= '</div>';
        $i++;
    }
}
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
            <?= Html::a('<i class="fas fa-arrow-left"></i> Voltar', ['index'], ['class' => 'btn btn-outline-secondary mr-1']) ?>
            <?= Html::a('<i class="fas fa-edit"></i> Editar', ['update', 'id' => $model->id], ['class' => 'btn btn-primary mr-1']) ?>
            <?= Html::a('<i class="fas fa-trash"></i> Apagar', ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => 'Tem a certeza que deseja eliminar este animal?',
                    'method' => 'post',
                ],
            ]) ?>
        </div>
    </div>

    <div class="row align-items-stretch">
        <div class="col-lg-5 mb-4">
            <div class="card shadow border-0 overflow-hidden h-100">
                <div class="card-header bg-white border-0 pt-3 pb-0">
                    <h5 class="card-title text-muted"><i class="fas fa-images mr-2"></i>Galeria</h5>
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
                                    <span class="sr-only">Anterior</span>
                                </a>
                                <a class="carousel-control-next" href="#animalCarousel" role="button" data-slide="next">
                                    <span class="carousel-control-next-icon bg-dark rounded-circle p-2" aria-hidden="true"></span>
                                    <span class="sr-only">Próximo</span>
                                </a>
                            <?php endif; ?>

                        </div>

                    <?php else: ?>
                        <div class="text-center text-muted p-5">
                            <i class="fas fa-camera fa-4x mb-3 text-secondary opacity-50"></i>
                            <p>Sem fotografias disponíveis</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-lg-7 mb-4">
            <div class="card shadow-sm border-0 h-100 d-flex flex-column">

                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-info-circle mr-2"></i>Ficha Técnica</h5>
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
                                'attribute' => 'user.username',
                                'contentOptions' => ['class' => 'text-primary align-middle'],
                                'captionOptions' => ['class' => 'align-middle'],
                            ],
                            [
                                'label' => 'Raça',
                                'attribute' => 'breed.description',
                                'contentOptions' => ['class' => 'font-weight-bold align-middle'],
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

<style>
    /* Animação de entrada suave */
    .fade-in-up {
        animation: fadeInUp 0.5s ease-out;
    }
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* Efeitos de Tabela */
    .table th {
        width: 30%;
        color: #6c757d;
        font-weight: 600;
        vertical-align: middle;
    }
    .table td {
        vertical-align: middle;
    }

    .carousel-control-prev-icon,
    .carousel-control-next-icon {
        background-size: 50%, 50%;
    }

    .mr-2 { margin-right: 0.5rem; }

    .content-header h1,
    .page-header h1 {
        display: none !important;
    }

    .content-header {
        padding: 0 !important;
        margin-bottom: 10px !important;
    }
</style>