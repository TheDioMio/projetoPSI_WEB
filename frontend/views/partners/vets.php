<?php

/* @var $this yii\web\View */

use yii\helpers\Html;

$this->title = 'Clínicas Veterinárias';
$this->params['breadcrumbs'][] = $this->title;

// eventualmente terá tabelas dedicadas na BD
$vets = [
    [
        'name' => 'Hospital Veterinário de Leiria',
        'location' => 'Leiria',
        'address' => 'R. de Olhalhas, Leiria',
        'description' => 'Serviço de urgências 24 horas, consultas, cirurgia e internamento com equipa multidisciplinar.',
        'link' => 'https://hvleiria.pt/',
    ],
    [
        'name' => 'Clínica Veterinária da Marinha Grande',
        'location' => 'Marinha Grande',
        'address' => 'Av. Victor Gallo, Marinha Grande',
        'description' => 'Cuidados de saúde animal personalizados, vacinação, desparasitação e aconselhamento nutricional.',
        'link' => 'https://www.facebook.com/clinica.vet.mg/',
    ],
    [
        'name' => 'Ani-Mar - Clínica Veterinária',
        'location' => 'Marinha Grande / São Pedro de Moel',
        'address' => 'Rua de Leiria, Marinha Grande',
        'description' => 'Clínica focada no bem-estar animal com serviços de diagnóstico, medicina preventiva e cirurgia.',
        'link' => 'https://www.ani-mar.pt/',
    ],
    [
        'name' => 'VetLeiria',
        'location' => 'Leiria',
        'address' => 'Rua de Santo André, Leiria',
        'description' => 'Especialistas em medicina interna, dermatologia e cardiologia para pequenos animais.',
        'link' => 'https://vetleiria.pt/',
    ],
    [
        'name' => 'Centro Veterinário de Leiria (CVL)',
        'location' => 'Leiria',
        'address' => 'Av. Marquês de Pombal, Leiria',
        'description' => 'Serviços de radiologia, ecografia e análises clínicas para cães, gatos e exóticos.',
        'link' => 'https://cvleiria.com/',
    ],
];
?>

<div class="site-vets container py-4">
    <h1 class="text-success mb-4"><i class="fas fa-stethoscope"></i> <?= Html::encode($this->title) ?></h1>
    <p class="lead mb-5">Encontre cuidados médicos de qualidade para os seus amigos de quatro patas na nossa região.</p>

    <div class="row">
        <?php foreach ($vets as $vet): ?>
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card h-100 shadow-sm border-0 border-top border-success border-3">
                    <div class="card-body">
                        <h3 class="h5 mb-2 text-dark"><?= Html::encode($vet['name']) ?></h3>
                        <div class="mb-2">
                            <span class="badge bg-light text-success border border-success">
                                <i class="fas fa-map-marker-alt"></i> <?= Html::encode($vet['location']) ?>
                            </span>
                        </div>
                        <p class="small text-secondary mb-3">
                            <i class="fas fa-map-pin"></i> <?= Html::encode($vet['address']) ?>
                        </p>
                        <p class="card-text text-muted small">
                            <?= Html::encode($vet['description']) ?>
                        </p>
                    </div>
                    <div class="card-footer bg-transparent border-0 pb-3">
                        <?= Html::a('Ver Contactos / Website', $vet['link'], [
                            'target' => '_blank',
                            'class' => 'btn btn-success btn-sm w-100 shadow-sm'
                        ]) ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="alert alert-warning mt-5 shadow-sm border-0">
        <h5 class="alert-heading"><i class="fas fa-ambulance"></i> Emergências</h5>
        <p class="mb-0 small">Em caso de emergência fora de horas, recomendamos contactar o Hospital Veterinário com serviço 24h mais próximo de si.</p>
    </div>
</div>