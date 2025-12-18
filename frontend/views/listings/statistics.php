<?php
use yii\helpers\Html;
use yii\web\JqueryAsset;

/** @var $stats array */

$this->title = 'As Minhas Estatísticas';
$this->registerJsFile(
    'https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.js',
    ['depends' => [JqueryAsset::class]]
);

?>

<div class="container py-5">

    <h2 class="mb-4 text-uppercase border-start border-5 border-primary ps-3"><?=Html::encode('Painel Estatísticas')?></h2>

    <div class="row g-4 mb-5">
        <div class="col-md-3">
            <div class="card bg-primary text-white h-100">
                <div class="card-body text-center">
                    <h1 class="display-4 fw-bold"><?= $stats['kpi']['animals'] ?></h1>
                    <p class="fs-5">Meus Animais</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white h-100">
                <div class="card-body text-center">
                    <h1 class="display-4 fw-bold"><?= $stats['kpi']['listings'] ?></h1>
                    <p class="fs-5">Anúncios Ativos</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white h-100">
                <div class="card-body text-center">
                    <h1 class="display-4 fw-bold"><?= $stats['kpi']['views'] ?></h1>
                    <p class="fs-5">Visualizações Totais</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-dark h-100">
                <div class="card-body text-center">
                    <h1 class="display-4 fw-bold"><?= $stats['kpi']['applications'] ?></h1>
                    <p class="fs-5">Candidaturas Recebidas</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-5">

        <div class="col-lg-8">

            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Animais Registados (Últimos 6 meses)</h5>
                </div>
                <div class="card-body">
                    <canvas id="trendChart" style="max-height: 300px;"></canvas>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <h4 class="mb-3 text-uppercase">Top Visualizações</h4>
            <div class="list-group shadow-sm">
                <?php foreach ($stats['topVistos'] as $listing): ?>
                    <a href="<?= \yii\helpers\Url::to(['detail', 'id' => $listing->animal_id]) ?>" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                        <div>
                            <div class="fw-bold"><?= Html::encode($listing->animal->name) ?></div>
                            <small class="text-muted"><?= Html::encode($listing->animal->animalType->description) ?></small>
                        </div>
                        <span class="badge bg-primary rounded-pill">
                            <i class="bi bi-eye"></i> <?= $listing->views ?>
                        </span>
                    </a>
                <?php endforeach; ?>

                <?php if(empty($stats['topVistos'])): ?>
                    <div class="list-group-item text-muted">Ainda não tem anúncios com visualizações.</div>
                <?php endif; ?>
            </div>

            <div class="mt-5">
                <h4 class="mb-3 text-uppercase">Meus Tipos de Animais</h4>
                <div class="card shadow-sm">
                    <div class="card-body">
                        <canvas id="typeChart" style="max-height: 250px;"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
// 1. Preparar dados para JS (Encode seguro)
$jsTrendLabels   = json_encode($stats['trend']['labels']);
$jsTrendData     = json_encode($stats['trend']['data']);

$jsAppLabels     = json_encode($stats['appStatus']['labels']);
$jsAppData       = json_encode($stats['appStatus']['data']);

$jsTypeLabels    = json_encode($stats['types']['labels']);
$jsTypeData      = json_encode($stats['types']['data']);

$this->registerJs("
    $(function () {
        
        // --- GRÁFICO 1: EVOLUÇÃO (LINHA) ---
        if ($('#trendChart').length) {
            new Chart($('#trendChart').get(0).getContext('2d'), {
                type: 'line',
                data: {
                    labels: $jsTrendLabels,
                    datasets: [{
                        label: 'Novos Animais',
                        backgroundColor: 'rgba(13, 110, 253, 0.1)', // Azul claro
                        borderColor: '#0d6efd', // Azul
                        data: $jsTrendData,
                        fill: true
                    }]
                },
                options: { 
                    responsive: true, 
                    maintainAspectRatio: false,
                    scales: { yAxes: [{ ticks: { beginAtZero: true, stepSize: 1 } }] }
                }
            });
        }

        // --- GRÁFICO 2: STATUS CANDIDATURAS (BARRA) ---
        if ($('#appChart').length) {
            new Chart($('#appChart').get(0).getContext('2d'), {
                type: 'bar',
                data: { 
                    labels: $jsAppLabels, 
                    datasets: [{ 
                        label: 'Total', 
                        backgroundColor: ['#ffc107', '#0dcaf0', '#198754', '#dc3545'], 
                        data: $jsAppData 
                    }] 
                },
                options: { 
                    maintainAspectRatio: false, 
                    legend: { display: false },
                    scales: { yAxes: [{ ticks: { beginAtZero: true, stepSize: 1 } }] }
                }
            });
        }

        // --- GRÁFICO 3: TIPOS DE ANIMAIS (PIE) ---
        if ($('#typeChart').length) {
            new Chart($('#typeChart').get(0).getContext('2d'), {
                type: 'pie',
                data: { 
                    labels: $jsTypeLabels, 
                    datasets: [{ 
                        data: $jsTypeData, 
                        backgroundColor: ['#fd7e14', '#20c997', '#6f42c1', '#6610f2'] 
                    }] 
                },
                options: { maintainAspectRatio: false }
            });
        }

    });
");
