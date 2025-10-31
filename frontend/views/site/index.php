<?php


use yii\helpers\Html;
use yii\helpers\Url;


/** @var yii\web\View $this */

$this->title = 'Início';
?>

<?php $this->beginBlock('hero'); ?>
<!-- Hero Start -->
<div class="container-fluid bg-primary py-5 mb-5 hero-header">
    <div class="container py-5">
        <div class="row justify-content-start">
            <div class="col-lg-8 text-center text-lg-start">
                <h1 class="display-1 text-dark mb-lg-4">PetPanion</h1>
                <h1 class="text-uppercase text-white mb-lg-4">A sua plataforma para adoção responsável</h1>
                <p class="fs-4 text-white mb-lg-4">Animais felizes, famílias completas.</p>
                <div class="d-flex align-items-center justify-content-center justify-content-lg-start pt-5">
                    <a href="../site/about" class="btn btn-outline-light border-2 py-md-3 px-md-5 me-5">Saber Mais</a>
                    <button type="button" class="btn-play" data-bs-toggle="modal"
                            data-src="https://www.youtube.com/embed/DWRcNpR6Kdc" data-bs-target="#videoModal">
                        <span></span>
                    </button>
                    <h5 class="font-weight-normal text-white m-0 ms-4 d-none d-sm-block">Play Video</h5>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Hero End -->

<?php $this->endBlock(); ?>

<!-- Video Modal Start -->
<div class="modal fade" id="videoModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content rounded-0">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Youtube Video</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- 16:9 aspect ratio -->
                <div class="ratio ratio-16x9">
                    <iframe class="embed-responsive-item" src="" id="video" allowfullscreen allowscriptaccess="always"
                            allow="autoplay"></iframe>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Video Modal End -->


<!-- About Start -->
<div class="container-fluid py-5">
    <div class="container">
        <div class="row gx-5">
            <div class="col-lg-5 mb-5 mb-lg-0" style="min-height: 500px;">
                <div class="position-relative h-100">
                    <img class="position-absolute w-100 h-100 rounded" src="../img/about.jpg" style="object-fit: cover;">
                </div>
            </div>
            <div class="col-lg-7">
                <div class="border-start border-5 border-primary ps-5 mb-5">
                    <h6 class="text-primary text-uppercase">Processo</h6>
                    <h1 class="display-5 text-uppercase mb-0">Como Funciona?</h1>
                </div>
                <h4 class="text-body mb-4">Saiba como pode adotar um animal ou como pode colocar um animal para adoção</h4>
                <div class="bg-light p-4">
                    <ul class="nav nav-pills justify-content-between mb-3" id="pills-tab" role="tablist">
                        <li class="nav-item w-50" role="presentation">
                            <button class="nav-link text-uppercase w-100 active" id="pills-1-tab" data-bs-toggle="pill"
                                    data-bs-target="#pills-1" type="button" role="tab" aria-controls="pills-1"
                                    aria-selected="true">Adotar</button>
                        </li>
                        <li class="nav-item w-50" role="presentation">
                            <button class="nav-link text-uppercase w-100" id="pills-2-tab" data-bs-toggle="pill"
                                    data-bs-target="#pills-2" type="button" role="tab" aria-controls="pills-2"
                                    aria-selected="false">Colocar para Adoção</button>
                        </li>
                    </ul>
                    <div class="tab-content" id="pills-tabContent">
                        <div class="tab-pane fade show active" id="pills-1" role="tabpanel" aria-labelledby="pills-1-tab">
                            <p class="mb-0">Através da nossa plataforma, pesquise por um dos animais disponíveis, após selecionar
                            um animal basta entrar em contacto com o anunciante ou associação para iniciar o processo de adoção.
                            <br><br>Pode também pedir mais informações sobre o animal ou agendar uma visita!</p>
                        </div>
                        <div class="tab-pane fade" id="pills-2" role="tabpanel" aria-labelledby="pills-2-tab">
                            <p class="mb-0">Tem um animal para dar para adoção?
                            <br><br>Registe-se na nossa plataforma e crie um anúncio, adicione fotos, preencha os dados básicos
                            e uma descrição, clique em publicar e o seu anuncio está pronto para ser visualizado por milhares
                            de pessoas!</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- About End -->

<!-- Products Start -->
<div class="container-fluid py-5">
    <div class="container">
        <div class="border-start border-5 border-primary ps-5 mb-5" style="max-width: 600px;">
            <h6 class="text-primary text-uppercase">Animais</h6>
            <h1 class="display-5 text-uppercase mb-0">Os nossos animais mais recentes</h1>
        </div>

        <div class="owl-carousel product-carousel">

            <?php foreach ($recentListings as $listing): ?>
                <?php
                $animal = $listing->animal;
                if ($animal === null) continue;
                ?>

                <div class="pb-5">
                    <div class="product-item position-relative bg-light d-flex flex-column text-center">

                        <img class="img-fluid mb-4" src="../img/blog-1.jpg" alt="<?= Html::encode($animal->name) ?>" style="object-fit: cover; height: 200px;">

                        <h6 class="text-uppercase"><?= Html::encode($animal->name) ?></h6>

                        <h5 class="text-primary mb-0">
                            <?= Html::encode($animal->animalType->description) ?>
                        </h5>

                        <div class="btn-action d-flex justify-content-center">
                            <?= Html::a(
                                '<i class="bi bi-eye"></i>',
                                ['/site/detail', 'id' => $animal->id],
                                ['class' => 'btn btn-primary py-2 px-3']
                            ) ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>

        </div>
    </div>
</div>
<!-- Products End -->

<!-- Services Start -->
<div class="container-fluid py-5">
    <div class="container">
        <div class="border-start border-5 border-primary ps-5 mb-5" style="max-width: 600px;">
            <h6 class="text-primary text-uppercase">Parceiros</h6>
            <h1 class="display-5 text-uppercase mb-0">Conheça os nossos parceiros</h1>
        </div>
        <div class="row g-5">
            <div class="col-md-6">
                <div class="service-item bg-light d-flex p-4">
                    <i class="fa-solid fa-paw fa-4x text-primary me-4"></i>
                    <div>
                        <h5 class="text-uppercase mb-3">Associações de Proteção Animal</h5>
                        <p>Conheças as associações que fazem parte da nossa comunidade</p>
                        <a class="text-primary text-uppercase" href="">Conhecer<i class="bi bi-chevron-right"></i></a>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="service-item bg-light d-flex p-4">
                    <i class="fa-solid fa-syringe fa-4x text-primary me-4"></i>
                    <div>
                        <h5 class="text-uppercase mb-3">Cuidados Veterinários</h5>
                        <p>Conheça as clínicas e hospitais que fazem parte da nossa comunidade </p>
                        <a class="text-primary text-uppercase" href="">Conhecer<i class="bi bi-chevron-right"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Services End -->



<!-- Offer Start -->
<div class="container-fluid bg-offer my-5 py-5">
    <div class="container py-5">
        <div class="row gx-5 justify-content-start">
            <div class="col-lg-7">
                <div class="border-start border-5 border-dark ps-5 mb-5">
                    <h6 class="text-dark text-uppercase">Anunciar</h6>
                    <h1 class="display-5 text-uppercase text-white mb-0">Tem um Animal para Adotar?</h1>
                </div>
                <p class="text-white mb-4">Sabemos que a vida muda. A nossa plataforma ajuda-o a encontrar uma nova família responsável para o seu companheiro.</p>
                <a href="" class="btn btn-light py-md-3 px-md-5 me-3">Criar Anúncio</a>
            </div>
        </div>
    </div>
</div>
<!-- Offer End -->

<!-- Team Start -->
<div class="container-fluid py-5">
    <div class="container">
        <div class="border-start border-5 border-primary ps-5 mb-5" style="max-width: 600px;">
            <h6 class="text-primary text-uppercase">A Nossa Equipa</h6>
            <h1 class="display-5 text-uppercase mb-0">Conheça os nossos Voluntários</h1>
        </div>
        <div class="owl-carousel team-carousel position-relative" style="padding-right: 25px;">
            <div class="team-item">
                <div class="position-relative overflow-hidden">
                    <img class="img-fluid w-100" src="../img/team-1.jpg" alt="">
                    <div class="team-overlay">
                        <div class="d-flex align-items-center justify-content-start">
                            <a class="btn btn-light btn-square mx-1" href="#"><i class="bi bi-twitter"></i></a>
                            <a class="btn btn-light btn-square mx-1" href="#"><i class="bi bi-facebook"></i></a>
                            <a class="btn btn-light btn-square mx-1" href="#"><i class="bi bi-linkedin"></i></a>
                        </div>
                    </div>
                </div>
                <div class="bg-light text-center p-4">
                    <h5 class="text-uppercase">Full Name</h5>
                    <p class="m-0">Designation</p>
                </div>
            </div>
            <div class="team-item">
                <div class="position-relative overflow-hidden">
                    <img class="img-fluid w-100" src="../img/team-2.jpg" alt="">
                    <div class="team-overlay">
                        <div class="d-flex align-items-center justify-content-start">
                            <a class="btn btn-light btn-square mx-1" href="#"><i class="bi bi-twitter"></i></a>
                            <a class="btn btn-light btn-square mx-1" href="#"><i class="bi bi-facebook"></i></a>
                            <a class="btn btn-light btn-square mx-1" href="#"><i class="bi bi-linkedin"></i></a>
                        </div>
                    </div>
                </div>
                <div class="bg-light text-center p-4">
                    <h5 class="text-uppercase">Full Name</h5>
                    <p class="m-0">Designation</p>
                </div>
            </div>
            <div class="team-item">
                <div class="position-relative overflow-hidden">
                    <img class="img-fluid w-100" src="../img/team-3.jpg" alt="">
                    <div class="team-overlay">
                        <div class="d-flex align-items-center justify-content-start">
                            <a class="btn btn-light btn-square mx-1" href="#"><i class="bi bi-twitter"></i></a>
                            <a class="btn btn-light btn-square mx-1" href="#"><i class="bi bi-facebook"></i></a>
                            <a class="btn btn-light btn-square mx-1" href="#"><i class="bi bi-linkedin"></i></a>
                        </div>
                    </div>
                </div>
                <div class="bg-light text-center p-4">
                    <h5 class="text-uppercase">Full Name</h5>
                    <p class="m-0">Designation</p>
                </div>
            </div>
            <div class="team-item">
                <div class="position-relative overflow-hidden">
                    <img class="img-fluid w-100" src="../img/team-4.jpg" alt="">
                    <div class="team-overlay">
                        <div class="d-flex align-items-center justify-content-start">
                            <a class="btn btn-light btn-square mx-1" href="#"><i class="bi bi-twitter"></i></a>
                            <a class="btn btn-light btn-square mx-1" href="#"><i class="bi bi-facebook"></i></a>
                            <a class="btn btn-light btn-square mx-1" href="#"><i class="bi bi-linkedin"></i></a>
                        </div>
                    </div>
                </div>
                <div class="bg-light text-center p-4">
                    <h5 class="text-uppercase">Full Name</h5>
                    <p class="m-0">Designation</p>
                </div>
            </div>
            <div class="team-item">
                <div class="position-relative overflow-hidden">
                    <img class="img-fluid w-100" src="../img/team-5.jpg" alt="">
                    <div class="team-overlay">
                        <div class="d-flex align-items-center justify-content-start">
                            <a class="btn btn-light btn-square mx-1" href="#"><i class="bi bi-twitter"></i></a>
                            <a class="btn btn-light btn-square mx-1" href="#"><i class="bi bi-facebook"></i></a>
                            <a class="btn btn-light btn-square mx-1" href="#"><i class="bi bi-linkedin"></i></a>
                        </div>
                    </div>
                </div>
                <div class="bg-light text-center p-4">
                    <h5 class="text-uppercase">Full Name</h5>
                    <p class="m-0">Designation</p>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Team End -->


<!-- Testimonial Start -->
<div class="border-start border-5 border-primary ps-5 mb-5" style="max-width: 600px;">
    <h1 class="display-5 text-uppercase mb-0">Casos de sucesso</h1>
</div>
<div class="container-fluid bg-testimonial py-5" style="margin: 45px 0;">
    <div class="container py-5">
        <div class="row justify-content-end">
            <div class="col-lg-7">
                <div class="owl-carousel testimonial-carousel bg-white p-5">
                    <div class="testimonial-item text-center">
                        <div class="position-relative mb-4">
                            <img class="img-fluid mx-auto" src="../img/testimonial-1.jpg" alt="">
                            <div class="position-absolute top-100 start-50 translate-middle d-flex align-items-center justify-content-center bg-white" style="width: 45px; height: 45px;">
                                <i class="bi bi-chat-square-quote text-primary"></i>
                            </div>
                        </div>
                        <p>Dolores sed duo clita tempor justo dolor et stet lorem kasd labore dolore lorem ipsum. At lorem lorem magna ut et, nonumy et labore et tempor diam tempor erat. Erat dolor rebum sit ipsum.</p>
                        <hr class="w-25 mx-auto">
                        <h5 class="text-uppercase">Client Name</h5>
                        <span>Profession</span>
                    </div>
                    <div class="testimonial-item text-center">
                        <div class="position-relative mb-4">
                            <img class="img-fluid mx-auto" src="../img/testimonial-2.jpg" alt="">
                            <div class="position-absolute top-100 start-50 translate-middle d-flex align-items-center justify-content-center bg-white" style="width: 45px; height: 45px;">
                                <i class="bi bi-chat-square-quote text-primary"></i>
                            </div>
                        </div>
                        <p>Dolores sed duo clita tempor justo dolor et stet lorem kasd labore dolore lorem ipsum. At lorem lorem magna ut et, nonumy et labore et tempor diam tempor erat. Erat dolor rebum sit ipsum.</p>
                        <hr class="w-25 mx-auto">
                        <h5 class="text-uppercase">Client Name</h5>
                        <span>Profession</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Testimonial End -->


<!-- Blog Start -->
<div class="container-fluid py-5">
    <div class="container">
        <div class="border-start border-5 border-primary ps-5 mb-5" style="max-width: 600px;">
            <h6 class="text-primary text-uppercase">Os nossos números</h6>
            <h1 class="display-5 text-uppercase mb-0">O Nosso Impacto</h1>
        </div>

    </div>

    <div class="container-fluid bg-dark stats-banner my-5 py-5">
        <div class="container py-5">
            <div class="row g-5">
                <div class="col-lg-6 col-md-6 text-center">
                    <i class="fa fa-paw fa-3x text-white mb-3"></i>

                    <h1 class="display-4 text-white counter-up"> <?= Html::encode($paraAdocaoCount) ?> </h1>

                    <h5 class="text-uppercase text-light">Animais para Adoção</h5>
                </div>

                <div class="col-lg-6 col-md-6 text-center">
                    <i class="fa fa-heart fa-3x text-white mb-3"></i>

                    <h1 class="display-4 text-white counter-up"> <?= Html::encode($adotadosCount) ?></h1>

                    <h5 class="text-uppercase text-light">Histórias de Sucesso</h5>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- Blog End -->

<a href="#" class="btn btn-primary py-3 fs-4 back-to-top"><i class="bi bi-arrow-up"></i></a>



<!--
<div class="site-index">
    <div class="p-5 mb-4 bg-transparent rounded-3">
        <div class="container-fluid py-5 text-center">
            <h1 class="display-4">Congratulations!</h1>
            <p class="fs-5 fw-light">You have successfully created your Yii-powered application.</p>
            <p><a class="btn btn-lg btn-success" href="https://www.yiiframework.com">Get started with Yii</a></p>
        </div>
    </div>

    <div class="body-content">

        <div class="row">
            <div class="col-lg-4">
                <h2>Heading</h2>

                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et
                    dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip
                    ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu
                    fugiat nulla pariatur.</p>

                <p><a class="btn btn-outline-secondary" href="https://www.yiiframework.com/doc/">Yii Documentation &raquo;</a></p>
            </div>
            <div class="col-lg-4">
                <h2>Heading</h2>

                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et
                    dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip
                    ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu
                    fugiat nulla pariatur.</p>

                <p><a class="btn btn-outline-secondary" href="https://www.yiiframework.com/forum/">Yii Forum &raquo;</a></p>
            </div>
            <div class="col-lg-4">
                <h2>Heading</h2>

                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et
                    dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip
                    ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu
                    fugiat nulla pariatur.</p>

                <p><a class="btn btn-outline-secondary" href="https://www.yiiframework.com/extensions/">Yii Extensions &raquo;</a></p>
            </div>
        </div>

    </div>
</div>
-->