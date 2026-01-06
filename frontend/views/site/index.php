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
                <h1 class="display-1 text-dark mb-lg-4"><?=Html::encode('PetPanion')?></h1>
                <h1 class="text-uppercase text-white mb-lg-4"><?=Html::encode('A sua plataforma para adoção responsável')?></h1>
                <p class="fs-4 text-white mb-lg-4"><?=Html::encode('Animais felizes, famílias completas.')?></p>
                <div class="d-flex align-items-center justify-content-center justify-content-lg-start pt-5">
                    <a href="../site/about" class="btn btn-outline-light border-2 py-md-3 px-md-5 me-5"><?=Html::encode('Saber Mais')?></a>
                    <button type="button" class="btn-play" data-bs-toggle="modal"
                            data-src="https://www.youtube.com/embed/NeQM1c-XCDc?si=w_nLHsghw1txRYEc" data-bs-target="#videoModal">
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
                <h5 class="modal-title" id="exampleModalLabel"><?=Html::encode('Youtube Video')?></h5>
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
                    <img class="position-absolute w-100 h-100 rounded" src="<?= Yii::getAlias('@web') ?>/img/about.jpg" style="object-fit: cover;">
                </div>
            </div>
            <div class="col-lg-7">
                <div class="border-start border-5 border-primary ps-5 mb-5">
                    <h6 class="text-primary text-uppercase"><?=Html::encode('Processo')?></h6>
                    <h1 class="display-5 text-uppercase mb-0"><?=Html::encode('Como Funciona?')?></h1>
                </div>
                <h4 class="text-body mb-4"><?=Html::encode('Saiba como pode adotar um animal ou como pode colocar um animal para adoção')?></h4>
                <div class="bg-light p-4">
                    <ul class="nav nav-pills justify-content-between mb-3" id="pills-tab" role="tablist">
                        <li class="nav-item w-50" role="presentation">
                            <button class="nav-link text-uppercase w-100 active" id="pills-1-tab" data-bs-toggle="pill"
                                    data-bs-target="#pills-1" type="button" role="tab" aria-controls="pills-1"
                                    aria-selected="true"><?=Html::encode('Adotar')?></button>
                        </li>
                        <li class="nav-item w-50" role="presentation">
                            <button class="nav-link text-uppercase w-100" id="pills-2-tab" data-bs-toggle="pill"
                                    data-bs-target="#pills-2" type="button" role="tab" aria-controls="pills-2"
                                    aria-selected="false"><?=Html::encode('Colocar Para Adoção')?></button>
                        </li>
                    </ul>
                    <div class="tab-content" id="pills-tabContent">
                        <div class="tab-pane fade show active" id="pills-1" role="tabpanel" aria-labelledby="pills-1-tab">
                            <p class="mb-0"><?=Html::encode('AAtravés da nossa plataforma, pesquise por um dos animais disponíveis, após selecionar
                            um animal basta entrar em contacto com o anunciante ou associação para iniciar o processo de adoção.')?>
                            <br><br><?=Html::encode('Pode também pedir mais informações sobre o animal ou agendar uma visita!')?></p>
                        </div>
                        <div class="tab-pane fade" id="pills-2" role="tabpanel" aria-labelledby="pills-2-tab">
                            <p class="mb-0"><?=Html::encode('Tem um animal para dar para adoção?')?>
                            <br><br><?=Html::encode('Registe-se na nossa plataforma e crie um anúncio, adicione fotos, preencha os dados básicos
                            e uma descrição, clique em publicar e o seu anuncio está pronto para ser visualizado por milhares
                            de pessoas!')?>
                            </p>
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
        <div class="border-start border-5 border-primary ps-5 mb-5" style="max-width: 600px">
            <h6 class="text-primary text-uppercase"><?=Html::encode('Animais')?></h6>
            <h1 class="display-5 text-uppercase mb-0"><?=Html::encode('Os nossos animais mais recentes')?></h1>
        </div>

        <div class="owl-carousel product-carousel">
            <?php foreach ($recentListings as $listing): ?>
                <?php
                $animal = $listing->animal;
                if ($animal === null) continue;
                ?>
                <div class="pb-5">
                    <div class="product-item position-relative bg-light d-flex flex-column text-center">

                        <?php $primaryImage = $animal->primaryImage; ?>
                        <img class="img-fluid mb-4"
                             src="<?= $primaryImage ? Yii::getAlias('@web') . '/' . Html::encode($primaryImage->path) : Yii::getAlias('@web') . '/images/no-image.png' ?>"
                             alt="<?= Html::encode($animal->name) ?>"
                             style="object-fit: cover; height: 200px;">

                        <h6 class="text-uppercase"><?= Html::encode($animal->name) ?></h6>

                        <h5 class="text-primary mb-0">
                            <?= Html::encode($animal->animalType->description) ?>
                        </h5>

                        <div class="btn-action d-flex justify-content-center">
                            <?= Html::a(
                                '<i class="bi bi-eye"></i>',
                                ['/listings/detail', 'id' => $animal->id],
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

        <div class="border-start border-5 border-primary ps-5 mb-5" style="max-width: 600px">
            <h6 class="text-primary text-uppercase"><?=Html::encode('Parceiros')?></h6>
            <h1 class="display-5 text-uppercase mb-0"><?=Html::encode('Conheça os nossos parceiros')?></h1>
        </div>


        <div class="row g-4"> <div class="col-lg-4 col-md-6">
                <div class="service-item bg-light d-flex p-4 h-100 align-items-center"> <i class="fa-solid fa-paw fa-3x text-primary me-3"></i>
                    <div>
                        <h5 class="text-uppercase mb-2"><?=Html::encode('Associações')?></h5>
                        <p class="small mb-2"><?=Html::encode('Conheça as associações parceiras.')?></p>
                        <?= Html::a(
                                'Conhecer <i class="bi bi-arrow-right"></i>',
                                ['/partners/shelters'],
                                ['class' => 'text-primary text-uppercase small fw-bold'])
                        ?>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6">
                <div class="service-item bg-light d-flex p-4 h-100 align-items-center">
                    <i class="fa-solid fa-syringe fa-3x text-primary me-3"></i>
                    <div>
                        <h5 class="text-uppercase mb-2"><?=Html::encode('Veterinários')?></h5>
                        <p class="small mb-2"><?=Html::encode('Conheça as clínicas parceiras.')?></p>
                        <?= Html::a(
                                'Conhecer <i class="bi bi-arrow-right"></i>',
                                ['/partners/vets'],
                                ['class' => 'text-primary text-uppercase small fw-bold'])
                        ?>
                    </div>
                </div>
            </div>


            <?php if (Yii::$app->user->can('applyUserPro')):?>
                <div class="col-lg-4 col-md-12">
                    <div class="service-item bg-primary d-flex flex-column justify-content-center p-4 h-100 rounded shadow-sm">

                        <div class="d-flex align-items-center mb-4">
                            <i class="fa-solid fa-handshake fa-3x text-white me-3"></i>
                            <div>
                                <h5 class="text-uppercase mb-1 text-white"><?=Html::encode('Seja Parceiro')?></h5>
                                <p class="small mb-0 text-white-50"><?=Html::encode('Junte a sua instituição à nossa rede.')?></p>
                            </div>
                        </div>
                        <?= Html::a(
                            'Candidatar-se agora <i class="bi bi-arrow-right"></i>',
                            ['/application/apply-user-pro'],
                            [
                                'class' => 'btn btn-light text-primary fw-bold text-uppercase rounded-pill shadow-sm',
                            ]
                        ) ?>
                    </div>
                </div>
            <?php endif;?>
        </div>






<!--        <div class="row g-5">-->
<!--            <div class="col-md-6">-->
<!--                <div class="service-item bg-light d-flex p-4">-->
<!--                    <i class="fa-solid fa-paw fa-4x text-primary me-4"></i>-->
<!--                    <div>-->
<!--                        <h5 class="text-uppercase mb-3">--><?php //=Html::encode('Associações de Proteção Animal')?><!--</h5>-->
<!--                        <p>--><?php //=Html::encode('Conheça as associações que fazem parte da nossa comunidade')?><!--</p>-->
<!--                        --><?php //= Html::a(
//                            'Conhecer <i class="bi bi-chevron-right"></i>',
//                            ['/site/index'],
//                            ['class' => 'text-primary text-uppercase']
//                        ) ?>
<!--                    </div>-->
<!--                </div>-->
<!--            </div>-->
<!---->
<!--            <div class="col-md-6">-->
<!--                <div class="service-item bg-light d-flex p-4">-->
<!--                    <i class="fa-solid fa-syringe fa-4x text-primary me-4"></i>-->
<!--                    <div>-->
<!--                        <h5 class="text-uppercase mb-3">--><?php //=Html::encode('Cuidados Veterinários')?><!--</h5>-->
<!--                        <p>--><?php //=Html::encode('Conheça as clínicas e hospitais que fazem parte da nossa comunidade')?><!--</p>-->
<!--                        --><?php //= Html::a(
//                            'Conhecer <i class="bi bi-chevron-right"></i>',
//                            ['/site/index'],
//                            ['class' => 'text-primary text-uppercase']
//                        ) ?>
<!--                    </div>-->
<!--                </div>-->
<!--            </div>-->
<!--        </div>-->
<!---->
<!--        <div class="row mt-5">-->
<!--            <div class="col-12">-->
<!--                <div class="bg-primary rounded-3 p-4 p-sm-5 text-center shadow-sm">-->
<!--                    <h3 class="text-white fw-bold mb-3">-->
<!--                        --><?php //= Html::encode('Representa uma Instituição ou Clínica?') ?>
<!--                    </h3>-->
<!--                    <p class="text-white fs-5 mb-4 opacity-75" style="max-width: 700px; margin: 0 auto;">-->
<!--                        --><?php //= Html::encode('Junte-se à nossa rede de parceiros e ajude-nos a encontrar lares felizes para mais animais.') ?>
<!--                    </p>-->
<!---->
<!--                    --><?php //= Html::a(
//                        'Tornar-se Parceiro',
//                        ['/site/contact'],
//                        [
//                            'class' => 'btn btn-light btn-lg text-primary fw-bold px-5 py-3 rounded-pill shadow-sm',
//                            'style' => 'transition: all 0.3s ease;',
//                        ]
//                    ) ?>
<!--                </div>-->
<!--            </div>-->
<!--        </div>-->
    </div>
</div>
<!-- Services End -->



<!-- Offer Start -->
<div class="container-fluid bg-offer my-5 py-5">
    <div class="container py-5">
        <div class="row gx-5 justify-content-start">
            <div class="col-lg-7">
                <div class="border-start border-5 border-dark ps-5 mb-5">
                    <h6 class="text-dark text-uppercase"><?=Html::encode('Anunciar')?></h6>
                    <h1 class="display-5 text-uppercase text-white mb-0"><?=Html::encode('Tem um animal para dar para adoção?')?></h1>
                </div>
                <p class="text-white mb-4"><?=Html::encode('Sabemos que a vida muda. A nossa plataforma ajuda-o a encontrar uma nova família responsável para o seu companheiro.')?></p>
                <?= Html::a(
                    'Criar Anúncio',
                    ['/listings/create-listing'],
                    ['class' => 'btn btn-light py-md-3 px-md-5 me-3']
                ) ?>
            </div>
        </div>
    </div>
</div>
<!-- Offer End -->

<!-- Team Start -->
<div class="container-fluid py-5">
    <div class="container">
        <div class="border-start border-5 border-primary ps-5 mb-5" style="max-width: 600px">
            <h6 class="text-primary text-uppercase"><?=Html::encode('A Nossa Equipa')?></h6>
            <h1 class="display-5 text-uppercase mb-0"><?=Html::encode('Conheça os nossos Voluntários')?></h1>
        </div>
        <div class="owl-carousel team-carousel position-relative pr-4">

            <div class="team-item">
                <div class="position-relative overflow-hidden">
                    <img class="img-fluid w-100" src="<?= Yii::getAlias('@web') ?>/img/team-1.jpg" alt="João Silva">
                    <div class="team-overlay">
                        <div class="d-flex align-items-center justify-content-start">
                            <a class="btn btn-light btn-square mx-1" href="#"><i class="bi bi-facebook"></i></a>
                            <a class="btn btn-light btn-square mx-1" href="#"><i class="bi bi-linkedin"></i></a>
                        </div>
                    </div>
                </div>
                <div class="bg-light text-center p-4">
                    <h5 class="text-uppercase">João Silva</h5>
                    <p class="m-0 text-primary fw-bold">Gestor de Projeto</p>
                </div>
            </div>

            <div class="team-item">
                <div class="position-relative overflow-hidden">
                    <img class="img-fluid w-100" src="<?= Yii::getAlias('@web') ?>/img/team-2.jpg" alt="Ana Costa">
                    <div class="team-overlay">
                        <div class="d-flex align-items-center justify-content-start">
                            <a class="btn btn-light btn-square mx-1" href="#"><i class="bi bi-facebook"></i></a>
                            <a class="btn btn-light btn-square mx-1" href="#"><i class="bi bi-linkedin"></i></a>
                        </div>
                    </div>
                </div>
                <div class="bg-light text-center p-4">
                    <h5 class="text-uppercase">Ana Costa</h5>
                    <p class="m-0 text-primary fw-bold">Full-Stack Developer</p>
                </div>
            </div>

            <div class="team-item">
                <div class="position-relative overflow-hidden">
                    <img class="img-fluid w-100" src="<?= Yii::getAlias('@web') ?>/img/team-3.jpg" alt="Pedro Santos">
                    <div class="team-overlay">
                        <div class="d-flex align-items-center justify-content-start">
                            <a class="btn btn-light btn-square mx-1" href="#"><i class="bi bi-facebook"></i></a>
                            <a class="btn btn-light btn-square mx-1" href="#"><i class="bi bi-linkedin"></i></a>
                        </div>
                    </div>
                </div>
                <div class="bg-light text-center p-4">
                    <h5 class="text-uppercase">Pedro Santos</h5>
                    <p class="m-0 text-primary fw-bold">Especialista de UI/UX</p>
                </div>
            </div>

            <div class="team-item">
                <div class="position-relative overflow-hidden">
                    <img class="img-fluid w-100" src="<?= Yii::getAlias('@web') ?>/img/team-4.jpg" alt="Maria Oliveira">
                    <div class="team-overlay">
                        <div class="d-flex align-items-center justify-content-start">
                            <a class="btn btn-light btn-square mx-1" href="#"><i class="bi bi-facebook"></i></a>
                            <a class="btn btn-light btn-square mx-1" href="#"><i class="bi bi-linkedin"></i></a>
                        </div>
                    </div>
                </div>
                <div class="bg-light text-center p-4">
                    <h5 class="text-uppercase">Maria Oliveira</h5>
                    <p class="m-0 text-primary fw-bold">Gestão de Parcerias</p>
                </div>
            </div>

            <div class="team-item">
                <div class="position-relative overflow-hidden">
                    <img class="img-fluid w-100" src="<?= Yii::getAlias('@web') ?>/img/team-5.jpg" alt="André Sousa">
                    <div class="team-overlay">
                        <div class="d-flex align-items-center justify-content-start">
                            <a class="btn btn-light btn-square mx-1" href="#"><i class="bi bi-facebook"></i></a>
                            <a class="btn btn-light btn-square mx-1" href="#"><i class="bi bi-linkedin"></i></a>
                        </div>
                    </div>
                </div>
                <div class="bg-light text-center p-4">
                    <h5 class="text-uppercase">André Sousa</h5>
                    <p class="m-0 text-primary fw-bold">Apoio ao Adotante</p>
                </div>
            </div>

        </div>
    </div>
</div>
<!-- Team End -->


<!-- Testimonial Start -->
<div class="border-start border-5 border-primary ps-5 mb-5" style="max-width: 600px">
    <h1 class="display-5 text-uppercase mb-0"><?=Html::encode('Casos de Sucesso')?></h1>
</div>
<div class="container-fluid bg-testimonial py-5" style="margin: 45px 0;">
    <div class="container py-5">
        <div class="row justify-content-end">
            <div class="col-lg-7">
                <div class="owl-carousel testimonial-carousel bg-white p-5">

                    <div class="testimonial-item text-center">
                        <div class="position-relative mb-4">
                            <img class="img-fluid mx-auto" src="<?= Yii::getAlias('@web') ?>/img/testimonial-1.jpg" alt="" style="border-radius: 50%; width: 100px; height: 100px; object-fit: cover;">
                            <div class="position-absolute top-100 start-50 translate-middle d-flex align-items-center justify-content-center bg-white" style="width: 45px; height: 45px; border-radius: 50%; border: 1px solid #eee;">
                                <i class="bi bi-chat-square-quote text-primary"></i>
                            </div>
                        </div>
                        <p>Adotei o 'Bobi' através desta plataforma e a experiência não podia ter sido melhor. O processo de candidatura foi simples e hoje ele é o rei da casa. Obrigado por facilitarem este encontro!</p>
                        <hr class="w-25 mx-auto">
                        <h5 class="text-uppercase">Ana Silva</h5>
                        <span>Professora, Leiria</span>
                    </div>

                    <div class="testimonial-item text-center">
                        <div class="position-relative mb-4">
                            <img class="img-fluid mx-auto" src="<?= Yii::getAlias('@web') ?>/img/testimonial-2.jpg" alt="" style="border-radius: 50%; width: 100px; height: 100px; object-fit: cover;">
                            <div class="position-absolute top-100 start-50 translate-middle d-flex align-items-center justify-content-center bg-white" style="width: 45px; height: 45px; border-radius: 50%; border: 1px solid #eee;">
                                <i class="bi bi-chat-square-quote text-primary"></i>
                            </div>
                        </div>
                        <p>Como voluntário numa associação local, esta plataforma ajudou-nos a encontrar famílias responsáveis para mais de 10 animais em apenas um mês. Uma ferramenta essencial para a causa animal.</p>
                        <hr class="w-25 mx-auto">
                        <h5 class="text-uppercase">Ricardo Santos</h5>
                        <span>Voluntário, Marinha Grande</span>
                    </div>

                    <div class="testimonial-item text-center">
                        <div class="position-relative mb-4">
                            <img class="img-fluid mx-auto" src="<?= Yii::getAlias('@web') ?>/img/testimonial-3.jpg" alt="" style="border-radius: 50%; width: 100px; height: 100px; object-fit: cover;">
                            <div class="position-absolute top-100 start-50 translate-middle d-flex align-items-center justify-content-center bg-white" style="width: 45px; height: 45px; border-radius: 50%; border: 1px solid #eee;">
                                <i class="bi bi-chat-square-quote text-primary"></i>
                            </div>
                        </div>
                        <p>Excelente iniciativa! A transparência nos perfis dos animais e a facilidade de contacto com os abrigos faz toda a diferença para quem quer adotar de forma consciente.</p>
                        <hr class="w-25 mx-auto">
                        <h5 class="text-uppercase">Marta Ferreira</h5>
                        <span>Designer, Batalha</span>
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
        <div class="border-start border-5 border-primary ps-5 mb-5" style="max-width: 600px">
            <h6 class="text-primary text-uppercase"><?=Html::encode('Os nossos números')?></h6>
            <h1 class="display-5 text-uppercase mb-0"><?=Html::encode('O Nosso Impacto')?></h1>
        </div>

    </div>

    <div class="container-fluid bg-dark stats-banner my-5 py-5">
        <div class="container py-5">
            <div class="row g-5">
                <div class="col-lg-6 col-md-6 text-center">
                    <i class="fa fa-paw fa-3x text-white mb-3"></i>

                    <h1 class="display-4 text-white counter-up"> <?= Html::encode($paraAdocaoCount) ?> </h1>

                    <h5 class="text-uppercase text-light"><?=Html::encode('Animais para Adoção')?></h5>
                </div>

                <div class="col-lg-6 col-md-6 text-center">
                    <i class="fa fa-heart fa-3x text-white mb-3"></i>

                    <h1 class="display-4 text-white counter-up"> <?= Html::encode($adotadosCount) ?></h1>

                    <h5 class="text-uppercase text-light"><?=Html::encode('Histórias de Sucesso')?></h5>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- Blog End -->

<a href="#" class="btn btn-primary py-3 fs-4 back-to-top"><i class="bi bi-arrow-up"></i></a>