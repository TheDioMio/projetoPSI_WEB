
<?php

use yii\helpers\Html;
use yii\helpers\StringHelper;

/** @var yii\web\View $this */
/** @var \yii\data\ActiveDataProvider $provider */
/** @var \app\models\Listing[] $listings */

$this->title = 'Os Meus Anúncios';
?>
<div class="container py-5">
    <h1 class="text-uppercase border-start border-5 border-primary ps-3 mb-4">
        <?= Html::encode($this->title) ?>
    </h1>
</div>
<div class="container py-5">
    <div class="row g-5">
        <div class="col-lg-8">

            <?php foreach ($listings as $listing): ?>
                <?php
                $animal = $listing->animal;
                if ($animal === null) continue;

                $primaryImage = $listing->animal->primaryImage;

                $imageUrl = $primaryImage
                    ? Yii::getAlias('@web') . '/' . $primaryImage->path
                    : Yii::getAlias('@web/img/placeholder.jpg');
                ?>

                <div class="blog-item mb-5">
                    <div class="row g-0 bg-light overflow-hidden">

                        <div class="col-12 col-sm-5 h-100">
                            <img class="img-fluid h-100"
                                 src="<?= Html::encode($imageUrl) ?>"
                                 style="object-fit: cover;">
                        </div>

                        <div class="col-12 col-sm-7 h-100 d-flex flex-column justify-content-center">
                            <div class="p-4">

                                <div class="d-flex mb-3">
                                    <small class="me-3">
                                        <i class="bi bi-bookmarks me-2"></i>
                                        <?= Html::encode($animal->animalType->description) ?>
                                    </small>

                                    <small>
                                        <i class="bi bi-calendar-date me-2"></i>
                                        <?= Yii::$app->formatter->asDate($listing->created_at, 'long') ?>
                                    </small>
                                </div>

                                <h5 class="text-uppercase mb-3"><?= Html::encode($animal->name) ?></h5>
                                <p class="card-text">
                                    <strong>Raça:</strong> <?= $listing->animal->breed->description ?? 'Sem informação' ?><br>
                                    <strong>Idade:</strong> <?= $listing->animal->animalAge->description ?? 'Sem informação' ?>
                                </p>
                                <p>
                                    <?= Html::encode(StringHelper::truncate($animal->description, 100, '...')) ?>
                                </p>

                                <!-- BOTÕES -->
                                <?= Html::a(
                                    'Editar <i class="bi bi-pencil"></i>',
                                    ['/listings/update', 'id' => $listing->id],
                                    ['class' => 'btn btn-warning btn-sm me-2']
                                ) ?>

                                <?= Html::a(
                                    'Apagar <i class="bi bi-trash"></i>',
                                    ['/listings/delete', 'id' => $listing->id],
                                    [
                                        'class' => 'btn btn-danger btn-sm',
                                        'data-confirm' => 'Tem a certeza que quer apagar este anúncio?',
                                        'data-method' => 'post',
                                    ]
                                ) ?>

                                <?= Html::a(
                                    'Ver Detalhe <i class="bi bi-chevron-right"></i>',
                                    ['/listings/detail', 'id' => $animal->id],
                                    ['class' => 'btn btn-primary btn-sm']
                                ) ?>

                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>

            <!-- PAGINAÇÃO -->
            <div class="col-12">
                <nav aria-label="Page navigation">
                    <?= \yii\widgets\LinkPager::widget([
                        'pagination' => $provider->pagination,
                        'options' => ['class' => 'pagination pagination-lg m-0'],
                        'linkContainerOptions' => ['class' => 'page-item'],
                        'linkOptions' => ['class' => 'page-link'],
                        'prevPageLabel' => '<i class="bi bi-arrow-left"></i>',
                        'nextPageLabel' => '<i class="bi bi-arrow-right"></i>',
                    ]) ?>
                </nav>
            </div>

        </div>

        <!-- SIDEBAR -->
        <div class="col-lg-4">
            <!-- podes copiar o sidebar do teu ficheiro se quiseres manter -->
        </div>
    </div>
</div>
