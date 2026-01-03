<?php

use hail812\adminlte\widgets\Menu;
use hail812\adminlte3\assets\AdminLteAsset;
use yii\bootstrap5\Html;
use yii\helpers\Url;
$this->registerCssFile('@web/css/style.css', [
    'depends' => [AdminLteAsset::class],
]);
$userLogado = Yii::$app->user->identity;
$backendBaseUrl = Yii::$app->request->baseUrl; // /projeto/backend/web
$frontendBaseUrl = str_replace('/backend/web', '/frontend/web', $backendBaseUrl); // /projeto/frontend/web
$avatar = '';
//2. Carregar a foto do user, concatenação para conseguirmos o URL certo
if ($userLogado->profileImage) {
    $avatar = $frontendBaseUrl . '/' . ltrim($userLogado->profileImage->path, '/');
}
?>
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="#" class="brand-link py-0">
        <?= Html::img('@web/img/logoBlack&White', [
            'alt' => 'Imagem logo Dashboard',
            'class' => 'img-logo-dashboard'
        ]) ?>
        <span class="brand-text font-weight-light"><?=Html::encode('Admin Panel')?></span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image d-flex justify-content-center align-items-center">
                <?php if ($avatar == null): ?>
                    <span class="fa-stack" style="font-size: 15px;">
                <i class="fas fa-circle fa-stack-2x text-white-50"></i>
                <i class="fas fa-user fa-stack-1x text-dark"></i>
            </span>
                <?php else: ?>
                    <img src="<?= $avatar ?>"
                         class="img-circle elevation-2"
                         alt="User Image"
                         style="width: 30px; height: 30px; object-fit: cover;">
                <?php endif; ?>
            </div>
            <div class="info">
                <?= Html::a(
                    $userLogado->username, //Texto que aparece no botão (nome do dono)
                    ['user/view', 'id' => $userLogado->id], //A rota para onde vai (backend/user/view)
                    [
                        'target' => '_blank',
                    ]
                );
                ?>
            </div>
        </div>

<!--        SidebarSearch Form -->
<!--        <div class="form-inline">-->
<!--            <div class="input-group" data-widget="sidebar-search">-->
<!--                <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">-->
<!--                <div class="input-group-append">-->
<!--                    <button class="btn btn-sidebar">-->
<!--                        <i class="fas fa-search fa-fw"></i>-->
<!--                    </button>-->
<!--                </div>-->
<!--            </div>-->
<!--        </div>-->
<!--        Sidebar Menu -->

        <nav class="mt-2">
            <?php
            echo Menu::widget([
                'items' => [
                    ['label' => 'Dashboard', 'url' => ['site/index'], 'icon' => 'tachometer-alt', 'iconStyle' => 'fas'],
                    ['label' => 'Estatísticas', 'url' => ['site/statistics'], 'icon' => 'chart-bar', 'iconStyle' => 'far'],
                    ['label' => 'CRUD e Consulta de BD', 'header' => true],
                    [
                        'label' => 'Menu Animais', 'icon'=>'fas fa-bone',
                        'items' => [
                            ['label' => 'Animais', 'url' => ['animal/index'], 'iconStyle' => 'far'],
                            ['label' => 'Tipos de Animal', 'url' => ['animal-type/index'], 'iconStyle' => 'far'],
                            ['label' => 'Raças', 'url' => ['breed/index'], 'iconStyle' => 'far'],
                            [
                                    'label' => 'Definições de Animais', 'iconStyle' => 'far',
                                'items' => [
                                    ['label' => 'Idade', 'url' => ['animal-age/index'], 'icon' => 'dot-circle'],
                                    ['label' => 'Tamanho', 'url' => ['animal-size/index'], 'icon' => 'dot-circle'],
                                    ['label' => 'Vacinas', 'url' => ['vaccination/index'], 'icon' => 'dot-circle'],
                                ]
                            ]
                        ]
                    ],
                    [
                        'label' => 'Menu Utilizadores', 'icon'=>'fas fa-users',
                        'items' => [
                            ['label' => 'Utilizadores', 'url' => ['user/index'], 'iconStyle' => 'far'],
                            ['label' => 'Listagens', 'url' => ['listing/index'], 'iconStyle' => 'far'],
                            ['label' => 'Candidaturas', 'url' => ['application/index'], 'iconStyle' => 'far'],
                            ['label' => 'Comentários', 'url' => ['comment/index'], 'iconStyle' => 'far'],
                        ]
                    ],
//                    ['label' => 'Yii2 PROVIDED', 'header' => true],
//                    ['label' => 'Login', 'url' => ['site/login'], 'icon' => 'sign-in-alt', 'visible' => Yii::$app->user->isGuest],
//                    ['label' => 'Gii',  'icon' => 'file-code', 'url' => ['/gii'], 'target' => '_blank'],
//                    ['label' => 'Debug', 'icon' => 'bug', 'url' => ['/debug'], 'target' => '_blank'],
//                    ['label' => 'MULTI LEVEL EXAMPLE', 'header' => true],
//                    [
//                        'label' => 'Level1',
//                        'items' => [
//                            ['label' => 'Level2', 'iconStyle' => 'far'],
//                            [
//                                'label' => 'Level2',
//                                'iconStyle' => 'far',
//                                'items' => [
//                                    ['label' => 'Level3', 'iconStyle' => 'far', 'icon' => 'dot-circle'],
//                                    ['label' => 'Level3', 'iconStyle' => 'far', 'icon' => 'dot-circle'],
//                                    ['label' => 'Level3', 'iconStyle' => 'far', 'icon' => 'dot-circle']
//                                ]
//                            ],
//                            ['label' => 'Level2', 'iconStyle' => 'far']
//                        ]
//                    ],
//                    ['label' => 'LABELS', 'header' => true],
//                    ['label' => 'Important', 'iconStyle' => 'far', 'iconClassAdded' => 'text-danger'],
//                    ['label' => 'Warning', 'iconClass' => 'nav-icon far fa-circle text-warning'],
//                    ['label' => 'Informational', 'iconStyle' => 'far', 'iconClassAdded' => 'text-info'],
                ],
            ]);
            ?>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>





<!---->
<!--[-->
<!--'label' => 'Level1',-->
<!--'items' => [-->
<!--['label' => 'Level2', 'iconStyle' => 'far'],-->
<!--[-->
<!--'label' => 'Level2',-->
<!--'iconStyle' => 'far',-->
<!--'items' => [-->
<!--['label' => 'Level3', 'iconStyle' => 'far', 'icon' => 'dot-circle'],-->
<!--['label' => 'Level3', 'iconStyle' => 'far', 'icon' => 'dot-circle'],-->
<!--['label' => 'Level3', 'iconStyle' => 'far', 'icon' => 'dot-circle']-->
<!--]-->
<!--],-->
<!--['label' => 'Level2', 'iconStyle' => 'far']-->
<!--]-->
<!--],-->