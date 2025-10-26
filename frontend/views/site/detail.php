<?php

/** @var yii\web\View $this */

$this->title = 'Detalhes';
?>
<!-- Blog Start -->
<div class="container py-5">
    <div class="row g-5">
        <div class="col-lg-8">

            <!-- Blog Detail Start -->
            <div id="carouselExampleIndicators" class="carousel slide">
                <div class="carousel-indicators">
                    <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                    <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1" aria-label="Slide 2"></button>
                </div>
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <img src="img/hero.jpg" class="d-block w-100" alt="...">
                    </div>
                    <div class="carousel-item">
                        <img src="img/offer.jpg" class="d-block w-100" alt="...">
                    </div>

                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                </button>
            </div>




            <div class="mb-5">

                <h1 class="text-uppercase mb-4">nome do bicho</h1>
                <p>descrição descrição descrição descrição descrição descrição descrição descrição
                    descrição descrição descrição descriçãodescrição descrição descrição descrição
                    descrição descrição descrição descriçãodescrição descrição descrição descrição</p>

            </div>
            <!-- Blog Detail End -->

            <iframe class="position-relative w-100 mb-5"
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d565.315930341978!2d-8.796304639772064!3d39.730868391230864!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xd2273a5f1339db1%3A0xbf6fd68c4f4fcccd!2sAssocia%C3%A7%C3%A3o%20Zo%C3%B3fila%20de%20Leiria%20-%20Fi%C3%A9is%20Amigos!5e1!3m2!1spt-PT!2spt!4v1761435578952!5m2!1spt-PT!2spt"
                    frameborder="0" style="height: 300px; border:0;" allowfullscreen="" aria-hidden="false"
                    tabindex="0"></iframe>



            <!-- Comment List Start -->
            <div class="mb-5">
                <h3 class="text-uppercase border-start border-5 border-primary ps-3 mb-4 pt-10">3 Comentários</h3>
                <div class="d-flex mb-4">
                    <img src="img/user.jpg" class="img-fluid" style="width: 45px; height: 45px;">
                    <div class="ps-3">
                        <h6><a href="">John Doe</a> <small><i>01 Jan 2045</i></small></h6>
                        <p>Diam amet duo labore stet elitr invidunt ea clita ipsum voluptua, tempor labore
                            accusam ipsum et no at. Kasd diam tempor rebum magna dolores sed eirmod</p>
                        <button class="btn btn-sm btn-light">Reply</button>
                    </div>
                </div>
                <div class="d-flex mb-4">
                    <img src="img/user.jpg" class="img-fluid" style="width: 45px; height: 45px;">
                    <div class="ps-3">
                        <h6><a href="">John Doe</a> <small><i>01 Jan 2045</i></small></h6>
                        <p>Diam amet duo labore stet elitr invidunt ea clita ipsum voluptua, tempor labore
                            accusam ipsum et no at. Kasd diam tempor rebum magna dolores sed eirmod</p>
                        <button class="btn btn-sm btn-light">Reply</button>
                    </div>
                </div>
                <div class="d-flex ms-5 mb-4">
                    <img src="img/user.jpg" class="img-fluid" style="width: 45px; height: 45px;">
                    <div class="ps-3">
                        <h6><a href="">John Doe</a> <small><i>01 Jan 2045</i></small></h6>
                        <p>Diam amet duo labore stet elitr invidunt ea clita ipsum voluptua, tempor labore
                            accusam ipsum et no at. Kasd diam tempor rebum magna dolores sed eirmod</p>
                        <button class="btn btn-sm btn-light">Reply</button>
                    </div>
                </div>
            </div>
            <!-- Comment List End -->

            <!-- Comment Form Start -->
            <div class="bg-light rounded p-5">
                <h3 class="text-uppercase border-start border-5 border-primary ps-3 mb-4">Deixe o seu comentário</h3>
                <form>
                    <div class="row g-3">
                        <div class="col-12">
                            <textarea class="form-control bg-white border-0" rows="5" placeholder="Escreva aqui o seu comentário"></textarea>
                        </div>
                        <div class="col-12">
                            <button class="btn btn-primary w-100 py-3" type="submit">Enviar</button>
                        </div>
                    </div>
                </form>
            </div>
            <!-- Comment Form End -->
        </div>

        <!-- Sidebar Start -->
        <div class="col-lg-4">

            <!-- Category Start -->
            <div class="mb-5">
                <h3 class="text-uppercase border-start border-5 border-primary ps-3 mb-4">Interessado?</h3>
                <div class="d-flex flex-column justify-content-start">
                    <a class="h5 bg-light py-2 px-3 mb-2" href="#"><i class="bi bi-arrow-right me-2"></i>Candidatura Online <i class="bi bi-input-cursor-text"></i></a>
                    <a class="h5 bg-light py-2 px-3 mb-2" href="#"><i class="bi bi-arrow-right me-2"></i>Pedir mais Informações <i class="bi bi-info-circle"></i></a>
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
                    <img class="img-fluid" src="img/blog-1.jpg" style="width: 100px; height: 100px; object-fit: cover;" alt="">
                    <a href="" class="h5 d-flex align-items-center bg-light px-3 mb-0">Lorem ipsum dolor sit amet adipis elit
                    </a>
                </div>
                <div class="d-flex overflow-hidden mb-3">
                    <img class="img-fluid" src="img/blog-2.jpg" style="width: 100px; height: 100px; object-fit: cover;" alt="">
                    <a href="" class="h5 d-flex align-items-center bg-light px-3 mb-0">Lorem ipsum dolor sit amet adipis elit
                    </a>
                </div>
                <div class="d-flex overflow-hidden mb-3">
                    <img class="img-fluid" src="img/blog-3.jpg" style="width: 100px; height: 100px; object-fit: cover;" alt="">
                    <a href="" class="h5 d-flex align-items-center bg-light px-3 mb-0">Lorem ipsum dolor sit amet adipis elit
                    </a>
                </div>
                <div class="d-flex overflow-hidden mb-3">
                    <img class="img-fluid" src="img/blog-1.jpg" style="width: 100px; height: 100px; object-fit: cover;" alt="">
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
