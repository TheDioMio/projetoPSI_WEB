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
            'class' => "navbar navbar-expand-lg bg-white navbar-light shadow-sm py-3 py-lg-0 px-3  sticky-top",

        ],

        'innerContainerOptions' => [
        'class' => 'container-fluid' ],


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
        $menuItems[] = [
            'label' => 'Login <i class="bi bi-arrow-right"></i>',
            'url' => ['/site/login'],
            'encode' => false,
            'linkOptions' => ['class' => "nav-item nav-link nav-contact bg-primary text-white px-5 ms-lg-5"]];
    }else {
        $menuItems[] = ['label' => Html::encode(Yii::$app->user->identity->username),
            'items' => [
                ['label' => 'Novo Anuncio', 'url' => ['/site/create-listing']],
                ['label' => 'Os Meus Anúncios', 'url' => ['/site/myListings']],
                ['label' => 'O meu perfil', 'url' => ['/site/profile']],
                ['label' =>
                    Html::beginForm(['/site/logout'], 'post')
                    . Html::submitButton('Logout', ['class' => 'btn btn-link'])
                    . Html::endForm(),
                    'encode' => false

                ],
            ],
            'options' => ['class' => 'nav-item dropdown'],
           // 'dropDownOptions' => ['class' => 'dropdown-menu dropdown-menu-lg-end'],
            'linkOptions' => [
                'class' => 'nav-link dropdown-toggle',
                'data-bs-toggle' => 'dropdown',
                'role' => 'button',
                'aria-expanded' => 'false'
            ],
        ];
    }



    echo Nav::widget([
        'options' => ['class' => 'navbar-nav ms-auto py-0 pe-3'],
        'encodeLabels' => false,
        'items' => $menuItems,
    ]);
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



<?php $this->endBody() ?>

</body>
</html>
<?php $this->endPage();
