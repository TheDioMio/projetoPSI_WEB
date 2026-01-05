<?php

/* @var $this yii\web\View */

use yii\helpers\Html;

$this->title = 'Parcerias e Abrigos';
$this->params['breadcrumbs'][] = $this->title;

// eventualmente terá tabelas dedicadas na BD
$partners = [
    [
        'name' => 'Associação Zoófila de Leiria (AZL)',
        'location' => 'Leiria',
        'description' => 'A AZL é uma associação sem fins lucrativos que acolhe animais abandonados no concelho de Leiria, promovendo o seu bem-estar e adoção responsável.',
        'link' => 'https://www.azlleiria.com/',
    ],
    [
        'name' => 'APAMG - Ass. Protetora dos Animais da Marinha Grande',
        'location' => 'Marinha Grande',
        'description' => 'Instituição dedicada ao resgate e proteção de animais em risco no concelho da Marinha Grande, gerindo o seu próprio abrigo.',
        'link' => 'https://www.facebook.com/apamg.associacao',
    ],
    [
        'name' => 'Animalife',
        'location' => 'Nacional',
        'description' => 'A Animalife é uma associação de sensibilização e apoio a famílias carenciadas e pessoas sem abrigo que tenham animais de estimação.',
        'link' => 'https://www.animalife.pt/',
    ],
    [
        'name' => 'União Zoófila',
        'location' => 'Lisboa',
        'description' => 'Uma das associações mais antigas de Portugal, dedicada ao abrigo e recuperação de cães e gatos abandonados em Lisboa.',
        'link' => 'https://www.uniaozoofila.org/',
    ],
    [
        'name' => 'Animais de Rua',
        'location' => 'Porto / Nacional',
        'description' => 'Focada no programa CED (Capturar, Esterilizar e Devolver) para controlo de colónias de gatos e apoio a animais de rua.',
        'link' => 'https://www.animaisderua.org/',
    ],
    [
        'name' => 'IRA - Intervenção e Resgate Animal',
        'location' => 'Nacional',
        'description' => 'Grupo especializado em resgate de animais em situações de perigo extremo, maus-tratos e emergências.',
        'link' => 'https://www.ira.pt/',
    ],
];
?>

<div class="site-partners container py-4">
    <h1 class="text-primary mb-4"><?= Html::encode($this->title) ?></h1>
    <p class="lead mb-5">Temos orgulho em divulgar estas entidades que dedicam a sua vida à proteção animal na nossa região e em todo o país.</p>

    <div class="row">
        <?php foreach ($partners as $partner): ?>
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card h-100 shadow-sm border-0 border-top border-primary border-3">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-2">
                            <h3 class="h5 mb-0 text-dark"><?= Html::encode($partner['name']) ?></h3>
                        </div>
                        <span class="badge bg-light text-primary border border-primary mb-3">
                            <i class="fas fa-map-marker-alt"></i> <?= Html::encode($partner['location']) ?>
                        </span>
                        <p class="card-text text-muted small">
                            <?= Html::encode($partner['description']) ?>
                        </p>
                    </div>
                    <div class="card-footer bg-transparent border-0 pb-3">
                        <?= Html::a('Saber mais', $partner['link'], [
                            'target' => '_blank',
                            'class' => 'btn btn-primary btn-sm w-100 shadow-sm'
                        ]) ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="alert alert-secondary mt-5 text-center small">
        <p class="mb-0"><strong>Nota Académica:</strong> Esta página faz parte de um projeto escolar (PSI). Não existem parcerias oficiais; esta secção serve para demonstração de funcionalidades e divulgação das causas locais.</p>
    </div>
</div>
