

<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\StringHelper;

/** @var yii\web\View $this */

$this->title = 'Animais';
?>

<?php
    $videoOptions = [
    ['videoFile' => 'banner1.mp4', 'posterFile' => 'banner1.png'],
    ];


$escolhaAleatoria = $videoOptions[array_rand($videoOptions)];

$videoUrl = Yii::getAlias('@web/video/' . $escolhaAleatoria['videoFile']);
$posterUrl = Yii::getAlias('@web/video/' . $escolhaAleatoria['posterFile']);

?>

<?php $this->beginBlock('hero'); ?>

<div class="container-fluid p-0 hero-video-banner">

    <video poster="<?= Html::encode($posterUrl) ?>" autoplay loop muted playsinline>
        <source src="<?= Html::encode($videoUrl) ?>" type="video/mp4">
        O seu browser não suporta vídeos HTML5.
    </video>

    <div class="banner-content container text-center">
        <h1 class="display-3 text-white mb-4">
            Encontre aqui o seu próximo companheiro
        </h1>
        <p class="lead text-white-50">
            Procure cães, gatos e outros animais que precisam de um lar.
        </p>
    </div>
</div>

<?php $this->endBlock(); ?>

<!-- Blog Start -->
<div class="container py-5">
    <div class="row g-5">
        <!-- Blog list Start -->
        <div class="col-lg-8">

            <?php foreach ($dataProvider->getModels() as $listing): ?>
                <?php
                // Para ser mais fácil, vamos buscar o animal que está "dentro" do anúncio
                $animal = $listing->animal;

                // Se, por alguma razão, o animal deste anúncio foi apagado,
                // saltamos este loop e passamos ao próximo
                if ($animal === null) {
                    continue;
                }

                $primaryImage = $listing->animal->primaryImage;

                $imageUrl = isset($primaryImage)
                    ? Yii::getAlias('@web') . '/' . $primaryImage->path
                    : Yii::getAlias('@web/img/placeholder.jpg');
                ?>

                <div class="blog-item mb-5">
                    <div class="row g-0 bg-light overflow-hidden">
                        <div class="col-12 col-sm-5 h-100">
                            <img class="img-fluid h-100" src="<?= Html::encode($imageUrl) ?>" style="object-fit: cover;">
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

                                <h5 class="text-uppercase mb-3"><?php echo $animal->name ?></h5>

                                <p class="card-text">
                                    <strong>Raça:</strong> <?= $listing->animal->breed->description ?? 'Sem informação' ?><br>
                                    <strong>Idade:</strong> <?= $listing->animal->animalAge->description ?? 'Sem informação' ?>
                                </p>

                                <p>
                                    <?= Html::encode(StringHelper::truncate($animal->description, 100, '...')) ?>
                                </p>

                                <?php
                                echo Html::a(
                                    'Detalhes<i class="bi bi-chevron-right"></i>',
                                    ['/listings/detail', 'id' => $animal->id],
                                    ['class' => 'text-primary text-uppercase']
                                );
                                ?>
                                <div class="text-end">
                                    <small class="me-3 ">
                                        <i class="bi bi-eye me-2"></i>
                                        <?= Html::encode($animal->listing->views) ?>
                                    </small>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?> <div class="col-12">
                <nav aria-label="Page navigation">

                    <?= \yii\widgets\LinkPager::widget([
                        'pagination' => $dataProvider->getPagination(),

                        // Classes gerais
                        'options' => [
                            'class' => 'pagination pagination-lg m-0',
                        ],
                        'linkContainerOptions' => ['class' => 'page-item'],
                        'linkOptions' => ['class' => 'page-link'],

                        'prevPageLabel' => '<i class="bi bi-arrow-left"></i>',
                        'nextPageLabel' => '<i class="bi bi-arrow-right"></i>',
                    ]) ?>

                </nav>
            </div>
        </div>
        <!-- Blog list End -->

        <!-- Sidebar Start -->
        <div class="col-lg-4">
            <!-- Search Form Start -->
            <div class="mb-5">
                <h3 class="text-uppercase border-start border-5 border-primary ps-3 mb-4">Pesquisa</h3>
                <div class="input-group">
                    <form method="get" action="<?= Url::to(['listings/animal']) ?>">
                    <input type="text" class="form-control p-3" name="ListingSearch[q]" placeholder="Pesquisa">
                    <input type="submit" class="btn btn-primary px-4"><i class="bi bi-search"></i>
                    </form>
                </div>
            </div>
            <!-- Search Form End -->

            <!-- Category Start -->
            <div class="mb-5">
                <h3 class="text-uppercase border-start border-5 border-primary ps-3 mb-4">Filtros</h3>
                <div class="d-flex flex-column justify-content-start">
                    <a class="h5 bg-light py-2 px-3 mb-2" href="#"><i class="bi bi-arrow-right me-2"></i>Especie</a>
                    <a class="h5 bg-light py-2 px-3 mb-2" href="#"><i class="bi bi-arrow-right me-2"></i>Raça</a>
                    <a class="h5 bg-light py-2 px-3 mb-2" href="#"><i class="bi bi-arrow-right me-2"></i>Web Development</a>
                    <a class="h5 bg-light py-2 px-3 mb-2" href="#"><i class="bi bi-arrow-right me-2"></i>Keyword Research</a>
                    <a class="h5 bg-light py-2 px-3 mb-2" href="#"><i class="bi bi-arrow-right me-2"></i>Email Marketing</a>
                </div>
            </div>
            <!-- Category End -->

            <!-- Recent Post Start -->
            <div class="mb-5">
                <h3 class="text-uppercase border-start border-5 border-primary ps-3 mb-4">Recent Post</h3>
                <div class="d-flex overflow-hidden mb-3">
                    <img class="img-fluid" src="../img/blog-1.jpg" style="width: 100px; height: 100px; object-fit: cover;" alt="">
                    <a href="" class="h5 d-flex align-items-center bg-light px-3 mb-0">Lorem ipsum dolor sit amet adipis elit
                    </a>
                </div>
                <div class="d-flex overflow-hidden mb-3">
                    <img class="img-fluid" src="../img/blog-2.jpg" style="width: 100px; height: 100px; object-fit: cover;" alt="">
                    <a href="" class="h5 d-flex align-items-center bg-light px-3 mb-0">Lorem ipsum dolor sit amet adipis elit
                    </a>
                </div>
                <div class="d-flex overflow-hidden mb-3">
                    <img class="img-fluid" src="../img/blog-3.jpg" style="width: 100px; height: 100px; object-fit: cover;" alt="">
                    <a href="" class="h5 d-flex align-items-center bg-light px-3 mb-0">Lorem ipsum dolor sit amet adipis elit
                    </a>
                </div>
                <div class="d-flex overflow-hidden mb-3">
                    <img class="img-fluid" src="../img/blog-1.jpg" style="width: 100px; height: 100px; object-fit: cover;" alt="">
                    <a href="" class="h5 d-flex align-items-center bg-light px-3 mb-0">Lorem ipsum dolor sit amet adipis elit
                    </a>
                </div>
                <div class="d-flex overflow-hidden mb-3">
                    <img class="img-fluid" src="../img/blog-2.jpg" style="width: 100px; height: 100px; object-fit: cover;" alt="">
                    <a href="" class="h5 d-flex align-items-center bg-light px-3 mb-0">Lorem ipsum dolor sit amet adipis elit
                    </a>
                </div>
            </div>
            <!-- Recent Post End -->

            <!-- Image Start -->
            <div class="mb-5">
                <img src="../img/blog-1.jpg" alt="" class="img-fluid rounded">
            </div>
            <!-- Image End -->

            <!-- Tags Start -->
            <div class="mb-5">
                <h3 class="text-uppercase border-start border-5 border-primary ps-3 mb-4">Tag Cloud</h3>
                <div class="d-flex flex-wrap m-n1">
                    <a href="" class="btn btn-primary m-1">Design</a>
                    <a href="" class="btn btn-primary m-1">Development</a>
                    <a href="" class="btn btn-primary m-1">Marketing</a>
                    <a href="" class="btn btn-primary m-1">SEO</a>
                    <a href="" class="btn btn-primary m-1">Writing</a>
                    <a href="" class="btn btn-primary m-1">Consulting</a>
                    <a href="" class="btn btn-primary m-1">Design</a>
                    <a href="" class="btn btn-primary m-1">Development</a>
                    <a href="" class="btn btn-primary m-1">Marketing</a>
                    <a href="" class="btn btn-primary m-1">SEO</a>
                    <a href="" class="btn btn-primary m-1">Writing</a>
                    <a href="" class="btn btn-primary m-1">Consulting</a>
                </div>
            </div>
            <!-- Tags End -->

            <!-- Plain Text Start -->
            <div>
                <h3 class="text-uppercase border-start border-5 border-primary ps-3 mb-4">Plain Text</h3>
                <div class="bg-light text-center" style="padding: 30px;">
                    <p>Vero sea et accusam justo dolor accusam lorem consetetur, dolores sit amet sit dolor clita kasd justo, diam accusam no sea ut tempor magna takimata, amet sit et diam dolor ipsum amet diam</p>
                    <a href="" class="btn btn-primary py-2 px-4">Read More</a>
                </div>
            </div>
            <!-- Plain Text End -->
        </div>
        <!-- Sidebar End -->
    </div>
</div>
<!-- Blog End -->