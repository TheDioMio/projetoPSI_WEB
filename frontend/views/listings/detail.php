<?php

use yii\helpers\Html;

/** @var yii\web\View $this */

$this->title = 'Detalhes';

$images = $model->files;
$totalImages = count($images);

// Inicializa variáveis para o HTML
$carouselIndicators = '';
$carouselItems = '';
$i = 0;






?>
<!-- Blog Start -->
<div class="container py-5">
    <div class="row g-5">
        <div class="col-lg-8">

            <?php foreach ($images as $image):
                $isActive = ($i === 0) ? 'active' : ''; // O primeiro item é sempre ativo

                // O seu ListingsController guarda o URL completo na propriedade 'path'
                $imageUrl = $image->url;

                // A. Constrói os Indicadores (os botões em baixo)
                $carouselIndicators .= '<button type="button" data-bs-target="#animalCarousel" data-bs-slide-to="' . $i . '" class="' . $isActive . '" aria-current="' . ($isActive ? 'true' : 'false') . '" aria-label="Slide ' . ($i + 1) . '"></button>';

                // B. Constrói os Itens (as imagens)
                $carouselItems .= '<div class="carousel-item ' . $isActive . '">';
                $carouselItems .= Html::img($imageUrl, [
                    'class' => 'd-block w-100 rounded',
                    'alt' => $model->name . ' - Foto ' . ($i + 1),
                    // Defina uma altura fixa para evitar saltos no layout. Use 'object-fit: cover' para preencher a área.
                    'style' => 'height: 450px; object-fit: contain;'
                ]);
                $carouselItems .= '</div>';

                $i++;
            endforeach; ?>

            <!-- Blog Detail Start -->
            <div id="carouselExampleIndicators" class="carousel-dark slide">
                <div id="animalCarousel" class="carousel slide" data-bs-ride="carousel">

                    <div class="carousel-indicators">
                        <?= $carouselIndicators ?>
                    </div>

                    <div class="carousel-inner">
                        <?= $carouselItems ?>
                    </div>

                    <?php if ($totalImages > 1): ?>
                        <button class="carousel-control-prev" type="button" data-bs-target="#animalCarousel" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Anterior</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#animalCarousel" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Próximo</span>
                        </button>
                    <?php endif; ?>
                </div>
            </div>

            <div class="mb-5">

                <h1 class="text-uppercase mb-4"><?= Html::encode($model->name)?></h1>
                <p> <?= Html::encode($model->description)?></p>

            </div>
            <!-- Blog Detail End -->

            <iframe class="position-relative w-100 mb-5"
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d565.315930341978!2d-8.796304639772064!3d39.730868391230864!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xd2273a5f1339db1%3A0xbf6fd68c4f4fcccd!2sAssocia%C3%A7%C3%A3o%20Zo%C3%B3fila%20de%20Leiria%20-%20Fi%C3%A9is%20Amigos!5e1!3m2!1spt-PT!2spt!4v1761435578952!5m2!1spt-PT!2spt"
                    frameborder="0" style="height: 300px; border:0;" allowfullscreen="" aria-hidden="false"
                    tabindex="0"></iframe>



            <!-- Comment List Start -->
            <div class="mb-5">
                <h3 class="text-uppercase border-start border-5 border-primary ps-3 mb-4 pt-10">
                    <?= count($comments) ?> Comentário(s)
                </h3>

                <?php if (!empty($comments)): ?>
                    <?php foreach ($comments as $comment): ?>
                        <div class="d-flex mb-4 <?= $comment->user_id ? 'ms-5' : '' ?>">
                            <img src="/img/user.jpg" class="img-fluid" style="width: 45px; height: 45px;">

                            <div class="ps-3">
                                <h6>
                                    <a href="#"><?= htmlspecialchars($comment->user->name) ?></a>
                                    <small><i><?= Yii::$app->formatter->asDate($comment->created_at) ?></i></small>
                                </h6>

                                <p><?= nl2br(htmlspecialchars($comment->text)) ?></p>

<!--                                <button class="btn btn-sm btn-light">Reply</button>-->
                            </div>
                        </div>
                    <?php endforeach; ?>

                <?php else: ?>
                    <p class="text-muted">Ainda não existem comentários.</p>
                <?php endif; ?>
            </div>
            <!-- Comment List End -->

            <!-- Comment Form Start -->

            <?php if (Yii::$app->user->can('createComment')): ?>
            <div class="bg-light rounded p-5">
                <?php $form = \yii\widgets\ActiveForm::begin([
                    'action' => ['/comment/create', 'listing_id' => $model->listing->id],
                ]);?>

                <div class="bg-light rounded p-5">
                    <h3 class="text-uppercase border-start border-5 border-primary ps-3 mb-4">
                        Deixe o seu comentário
                    </h3>
                    <?php
                    ?>
                    <?= $form->field($newComment, 'text')->textarea([
                        'rows' => 5,
                        'placeholder' => 'Escreva aqui o seu comentário'
                    ])->label(false) ?>

                    <button class="btn btn-primary w-100 py-3" type="submit">Enviar</button>
                </div>

                <?php \yii\widgets\ActiveForm::end(); ?>

            </div>
            <?php endif; ?>
            <!-- Comment Form End -->
        </div>




        <!-- Sidebar Start -->
        <div class="col-lg-4">

            <!-- Plain Text Start -->
            <div>
                <h3 class="text-uppercase border-start border-5 border-primary ps-3 mb-5">Perfil do Animal</h3>
                <div class="bg-light text-center" style="padding: 30px;">

                    <div class="bg-light p-3">
                        <ul class="list-group list-group-flush">

                            <li class="list-group-item d-flex justify-content-between align-items-center bg-light px-0">
                                <strong>Idade:</strong>
                                <span><?= Html::encode($model->animalAge ? $model->animalAge->description : '—') ?></span>
                            </li>

                            <li class="list-group-item d-flex justify-content-between align-items-center bg-light px-0">
                                <strong>Porte:</strong>
                                <span><?= Html::encode($model->size ? $model->size->description : '—') ?></span>
                            </li>

                            <li class="list-group-item d-flex justify-content-between align-items-center bg-light px-0">
                                <strong>Tipo:</strong>
                                <span> <?= Html::encode($model->animalType ? $model->animalType->description : '—') ?> </span>
                            </li>

                            <li class="list-group-item d-flex justify-content-between align-items-center bg-light px-0">
                                <strong>Raça:</strong>
                                <span><?= Html::encode($model->breed ? $model->breed->description : '—')?></span>
                            </li>

                            <li class="list-group-item d-flex justify-content-between align-items-center bg-light px-0">
                                <strong>Esterilizado:</strong>
                                <span><?= \yii\helpers\Html::encode($model->neutered ? 'Sim' : 'Não') ?></span>
                            </li>

                            <li class="list-group-item d-flex justify-content-between align-items-center bg-light px-0">
                                <strong>Vacinas:</strong>
                                <span><?= Html::encode($model->vaccination ? $model->vaccination->description : '—') ?></span>
                            </li>

                            <li class="list-group-item d-flex justify-content-between align-items-center bg-light px-0">
                                <strong>Localização:</strong>
                                <span><?= Html::encode($model->location)?></span>
                            </li>

                        </ul>
                    </div>
            </div>
            <!-- Plain Text End -->

            <!-- Category Start -->
            <div class="mb-5">
                <h3 class="text-uppercase border-start border-5 border-primary ps-3 mb-4 mt-4">Interessado?</h3>
                <div class="d-flex flex-column justify-content-start">
                    <?= Html::a(
                        '<i class="bi bi-arrow-right me-2"></i>Candidatura Online <i class="bi bi-input-cursor-text"></i>',
                        ['application/apply', 'animal_id' => $model->id],
                        ['class' => 'h5 bg-light py-2 px-3 mb-2']
                    ) ?>
                    <?= Html::a(
                        '<i class="bi bi-arrow-right me-2"></i>Pedir mais Informações <i class="bi bi-input-cursor-text"></i>',
                        ['message/create', 'user_id' => $model->user_id, "from"=>"listing", "listing_id"=>$model->id],
                        ['class' => 'h5 bg-light py-2 px-3 mb-2']
                    ) ?>

                    <a class="h5 bg-light py-2 px-3 mb-2" href="#"><i class="bi bi-arrow-right me-2"></i>Agendar Visita <i class="bi bi-calendar-date"></i></a>
                    <a class="h5 bg-light py-2 px-3 mb-2" href="#"><i class="bi bi-arrow-right me-2"></i>????????????</a>
                    <a class="h5 bg-light py-2 px-3 mb-2" href="#"><i class="bi bi-arrow-right me-2"></i>????????????</a>
                </div>
            </div>
            <!-- Category End -->

            <!-- Recent Post Start -->
            <div class="mb-5">
                <h3 class="text-uppercase border-start border-5 border-primary ps-3 mb-4">Animais Recentes</h3>
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
            </div>
            <!-- Recent Post End -->

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


        </div>
        <!-- Sidebar End -->
    </div>
</div>
<!-- Blog End -->
