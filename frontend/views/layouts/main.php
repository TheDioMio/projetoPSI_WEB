<?php

/** @var \yii\web\View $this */
/** @var string $content */

use common\widgets\Alert;
use frontend\assets\AppAsset;
use yii\bootstrap5\Breadcrumbs;
use yii\bootstrap5\Html;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;
use yii\bootstrap5\Dropdown;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title. ' | ' . Yii::$app->name) ?></title>
    <?php $this->head() ?>

    <?php $web = Yii::getAlias('@web'); ?>

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins&family=Roboto:wght@700&display=swap" rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="<?= $web ?>/lib/flaticon/font/flaticon.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="<?= $web ?>/lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">

    <!-- Template Stylesheet (ordem: bootstrap antes, style por último) -->
    <link href="<?= $web ?>/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= $web ?>/css/style.css" rel="stylesheet">

</head>
<body>

<?php $this->beginBody() ?>


    <?php


    // 1. Defina o caminho para a sua imagem
    // (O helper Html::img() resolve o @web automaticamente)
    $logoUrl = '@web/img/logo_cores.png'; // Altere para o caminho da sua imagem

    // 2. Crie a tag <img> com o helper do Yii
    $logoImg = Html::img($logoUrl, [
        'alt' => Html::encode(Yii::$app->name) . ' Logo', // Texto alternativo
        'class' => 'me-3', // Mantém a margem 'margin-right' que o ícone tinha
        'style' => 'width: 4rem; height: 4rem; object-fit: contain;' // Força o tamanho
    ]);


    NavBar::begin([
        'brandLabel' => '<h1 class="m-0 text-dark">' . $logoImg . Html::encode(Yii::$app->name) . '</h1>',
        'brandUrl' => Yii::$app->homeUrl,

        'options' => [
            'class' => "navbar navbar-expand-lg bg-white navbar-light shadow-sm py-3 py-lg-0 px-3 px-lg-0 sticky-top",

        ],

        'innerContainerOptions' => [
        'class' => 'container-fluid' ],// Isto substitui o 'container' por defeito


        'brandOptions'    => ['class' => 'navbar-brand m-0'],
        'collapseOptions' => ['id' => 'navbarCollapse'],


    ]);
    $menuItems = [
        ['label' => 'Início', 'url' => ['/site/index'], 'linkOptions' => ['class' => 'nav-link active']],
        ['label' => 'Animais', 'url' => ['/site/animal'], 'linkOptions' => ['class' => 'nav-link']],
        ['label' => 'Acerca', 'url' => ['/site/about'], 'linkOptions' => ['class' => 'nav-link']],
        ['label' => 'Contactos', 'url' => ['/site/contact'], 'linkOptions' => ['class' => 'nav-link',]],
    ];
    if (Yii::$app->user->isGuest) {
        $menuItems[] = ['label' => 'Registar', 'url' => ['/site/signup']];
    }

    echo Nav::widget([
        'options' => ['class' => 'navbar-nav ms-auto py-0'],
        'items' => $menuItems,
    ]);
    if (Yii::$app->user->isGuest) {
        echo Html::tag('div',Html::a('Login <i class="bi bi-arrow-right"></i>',['/site/login'],
            ['class' => ["nav-item nav-link nav-contact bg-primary text-white px-5 ms-lg-5"]]),
            ['class' => ['d-flex', 'align-items-center', 'py-0', 'me-0']],

        );
    } else {
        echo Html::beginForm(['/site/logout'], 'post', ['class' => 'd-flex'])
            . Html::submitButton(
                'Logout (' . Yii::$app->user->identity->username . ')',
                ['class' => 'btn btn-link'],

            )
            . Html::endForm();
    }
    NavBar::end();
    ?>



<?php if (isset($this->blocks['hero'])): ?>
    <?= $this->blocks['hero'] ?>
<?php endif; ?>

<main role="main" class="flex-shrink-0">
    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</main>


<!-- Footer Start -->
<div class="container-fluid bg-light mt-5 py-5">
    <div class="container pt-5">
        <div class="row g-5">
            <div class="col-lg-3 col-md-6">
                <h5 class="text-uppercase border-start border-5 border-primary ps-3 mb-4">Contacta-nos</h5>
                <p class="mb-4">Na PetPanion queremos o melhor para os nossos utilizadores e animais, se tiver alguma questão não hesite em contactar-nos</p>
                <p class="mb-2"><i class="bi bi-geo-alt text-primary me-2"></i>Leiria, Portugal</p>
                <p class="mb-2"><i class="bi bi-envelope-open text-primary me-2"></i>info@petpanion.pt</p>
                <p class="mb-0"><i class="bi bi-telephone text-primary me-2"></i>+351 244 875 627</p>
            </div>
            <div class="col-lg-3 col-md-6">
                <h5 class="text-uppercase border-start border-5 border-primary ps-3 mb-4">Links Rápidos</h5>
                <div class="d-flex flex-column justify-content-start">
                    <a class="text-body mb-2" href="#"><i class="bi bi-arrow-right text-primary me-2"></i>Início</a>
                    <a class="text-body mb-2" href="#"><i class="bi bi-arrow-right text-primary me-2"></i>Acerca</a>
                    <a class="text-body mb-2" href="#"><i class="bi bi-arrow-right text-primary me-2"></i>Os Nossos Serviços</a>
                    <a class="text-body mb-2" href="#"><i class="bi bi-arrow-right text-primary me-2"></i>Meet The Team</a>
                    <a class="text-body mb-2" href="#"><i class="bi bi-arrow-right text-primary me-2"></i>Latest Blog</a>
                    <a class="text-body" href="#"><i class="bi bi-arrow-right text-primary me-2"></i>Contacta-nos</a>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <h5 class="text-uppercase border-start border-5 border-primary ps-3 mb-4">Popular Links</h5>
                <div class="d-flex flex-column justify-content-start">
                    <a class="text-body mb-2" href="#"><i class="bi bi-arrow-right text-primary me-2"></i>Home</a>
                    <a class="text-body mb-2" href="#"><i class="bi bi-arrow-right text-primary me-2"></i>About Us</a>
                    <a class="text-body mb-2" href="#"><i class="bi bi-arrow-right text-primary me-2"></i>Our Services</a>
                    <a class="text-body mb-2" href="#"><i class="bi bi-arrow-right text-primary me-2"></i>Meet The Team</a>
                    <a class="text-body mb-2" href="#"><i class="bi bi-arrow-right text-primary me-2"></i>Latest Blog</a>
                    <a class="text-body" href="#"><i class="bi bi-arrow-right text-primary me-2"></i>Contact Us</a>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <h5 class="text-uppercase border-start border-5 border-primary ps-3 mb-4">Segue-nos</h5>



                <h6 class="text-uppercase mt-4 mb-3">As nossas redes sociais</h6>
                <div class="d-flex">
                    <a class="btn btn-outline-primary btn-square me-2" href="#"><i class="bi bi-twitter"></i></a>
                    <a class="btn btn-outline-primary btn-square me-2" href="#"><i class="bi bi-facebook"></i></a>
                    <a class="btn btn-outline-primary btn-square me-2" href="#"><i class="bi bi-linkedin"></i></a>
                    <a class="btn btn-outline-primary btn-square" href="#"><i class="bi bi-instagram"></i></a>
                </div>

            </div>
            <div class="col-12 text-center text-body">
                <a class="text-body" href="">Terms & Conditions</a>
                <span class="mx-1">|</span>
                <a class="text-body" href="">Privacy Policy</a>
                <span class="mx-1">|</span>
                <a class="text-body" href="">Customer Support</a>
                <span class="mx-1">|</span>
                <a class="text-body" href="">Payments</a>
                <span class="mx-1">|</span>
                <a class="text-body" href="">Help</a>
                <span class="mx-1">|</span>
                <a class="text-body" href="">FAQs</a>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid bg-dark text-white-50 py-4">
    <div class="container">
        <div class="row g-5">
            <div class="col-md-6 text-center text-md-start">
                <p class="mb-md-0">&copy; <a class="text-white" href="#">PetPanion</a>. All Rights Reserved.</p>
            </div>

        </div>
    </div>
</div>
<!-- Footer End -->

<a href="#" class="btn btn-primary py-3 fs-4 back-to-top"><i class="bi bi-arrow-up"></i></a>

<!-- JavaScript Libraries -->
<?php $web = Yii::getAlias('@web'); ?>

<!-- 1) jQuery (necessário para easing/waypoints/owl e para o teu main.js) -->
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<!-- fallback local opcional, caso o CDN falhe -->
<script>window.jQuery || document.write('<script src="<?= $web ?>/js/vendor/jquery-3.6.4.min.js"><\/script>')</script>

<!-- 2) Bootstrap 5 bundle (para o collapse da navbar) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- 3) Plugins do template (dependem de jQuery) -->
<script src="<?= $web ?>/lib/easing/easing.min.js"></script>
<script src="<?= $web ?>/lib/waypoints/waypoints.min.js"></script>
<script src="<?= $web ?>/lib/owlcarousel/owl.carousel.min.js"></script>

<!-- 4) Script do template (depende dos plugins acima) -->
<script src="<?= $web ?>/js/main.js"></script>


<?php $this->endBody() ?>

</body>
</html>
<?php $this->endPage();
