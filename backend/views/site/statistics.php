<?php
use hail812\adminlte\widgets\SmallBox;
use hail812\adminlte3\assets\AdminLteAsset;
use yii\helpers\Url;
use yii\bootstrap5\Html;
use yii\web\JqueryAsset;

$this->title = 'Dashboard de Inteligência';
$this->params['breadcrumbs'] = [['label' => $this->title]];
$this->registerCssFile('@web/css/style.css', ['depends' => [AdminLteAsset::class]]);

// 2. FORÇAR CARREGAMENTO DO CHART.JS (CDN)
$this->registerJsFile(
    'https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.js',
    ['depends' => [JqueryAsset::class]]
);
?>

    <div class="container-fluid">
        <h5 class="mb-2 text-dark font-weight-bold">
            <i class="fas fa-chart-line text-primary mr-2"></i> Performance da Plataforma
        </h5>
        <div class="row">
            <div class="col-md-8">
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h3 class="card-title">Crescimento (Últimos 6 Meses)</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="chart">
                            <canvas id="trendChart" style="min-height: 300px; height: 300px; max-height: 300px; max-width: 100%;"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-maroon card-outline">
                    <div class="card-header">
                        <h3 class="card-title">Status de Candidaturas</h3>
                    </div>
                    <div class="card-body">
                        <canvas id="appStatusChart" style="min-height: 300px; height: 300px; max-height: 300px; max-width: 100%;"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <h5 class="mb-2 mt-3 text-dark font-weight-bold">
            <i class="fas fa-notes-medical text-danger mr-2"></i> Inventário e Saúde Animal
        </h5>

        <div class="row">
            <div class="col-md-6">
                <div class="card card-navy card-outline">
                    <div class="card-header">
                        <h3 class="card-title">Top Raças</h3>
                    </div>
                    <div class="card-body">
                        <canvas id="breedChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card card-purple card-outline">
                    <div class="card-header">
                        <h3 class="card-title">Faixas Etárias</h3>
                    </div>
                    <div class="card-body">
                        <canvas id="ageChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card card-olive card-outline">
                    <div class="card-header">
                        <h3 class="card-title">Esterilização</h3>
                    </div>
                    <div class="card-body">
                        <canvas id="neuteredChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="card card-success card-outline">
                    <div class="card-header">
                        <h3 class="card-title">Plano de Vacinação</h3>
                    </div>
                    <div class="card-body">
                        <canvas id="vacChart" style="min-height: 200px; height: 200px; max-height: 200px; max-width: 100%;"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card card-warning card-outline">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-fire mr-1"></i> Animais Mais Populares</h3>
                    </div>
                    <div class="card-body p-0">
                        <ul class="products-list product-list-in-card pl-2 pr-2">
                            <?php if (!empty($topVistos)): ?>
                                <?php foreach ($topVistos as $listing):
                                    if(!$listing->animal) continue;
                                    ?>
                                    <li class="item">
                                        <div class="product-img">
                                            <div class="float-left p-2 bg-light rounded border text-center"
                                                 style="width: 50px;">
                                                <?php if ($avatar == null): ?>
                                                    <span class="fa-stack fa-4x">
                                                        <i class="fas fa-circle fa-stack-2x text-light"></i>
                                                        <i class="fas fa-user fa-stack-1x text-secondary"></i>
                                                    </span>
                                                <?php else: ?>
                                                    <img src="<?= $avatar ?>"
                                                         class="img-circle elevation-2 user-image"
                                                         alt="User Image"
                                                    >
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <div class="product-info">
                                            <a href="<?= Url::to(['/animal/view', 'id' => $listing->animal->id]) ?>"
                                               class="product-title">
                                                <?= Html::encode($listing->animal->name) ?>
                                                <span class="badge badge-warning float-right"><?= $listing->views ?> views</span>
                                            </a>
                                            <span class="product-description">
                                            <?= Html::encode($listing->animal->animalType->description ?? '-') ?>
                                            <small class="text-muted">• <?= Html::encode($listing->animal->location) ?></small>
                                        </span>
                                        </div>
                                    </li>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <li class="item text-center p-3 text-muted">Ainda sem visualizações.</li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <h5 class="mb-2 mt-3 text-dark font-weight-bold">
            <i class="fas fa-globe-europe text-info mr-2"></i> Perfil Social e Geográfico
        </h5>

        <div class="row">
            <div class="col-md-4">
                <div class="card card-info card-outline">
                    <div class="card-header">
                        <h3 class="card-title">Top Localizações</h3>
                    </div>
                    <div class="card-body">
                        <canvas id="locChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-danger card-outline">
                    <div class="card-header">
                        <h3 class="card-title">Habitação dos Candidatos</h3>
                    </div>
                    <div class="card-body">
                        <canvas id="homeChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-lightblue card-outline">
                    <div class="card-header">
                        <h3 class="card-title">Tipos de Utilizador</h3>
                    </div>
                    <div class="card-body">
                        <canvas id="roleChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                    </div>
                </div>
            </div>
        </div>

    </div>

<?php
// ===========================================================
// CONFIGURAÇÃO DOS GRÁFICOS (JAVASCRIPT)
// ===========================================================

// Preparar dados PHP para JS (Encode seguro)
$jsTrendLabels = json_encode($trendLabels);
$jsTrendApps = json_encode($trendDataApps);
$jsTrendAni = json_encode($trendDataAnimals);

$jsHomeData = json_encode($statsHabitacao);
$jsVacData = json_encode($vacData);
$jsLocLabels = json_encode($locLabels);
$jsLocData = json_encode($locData);

$jsAgeLabels = json_encode($ageLabels);
$jsAgeData = json_encode($ageData);
$jsBreedLabels = json_encode($breedLabels);
$jsBreedData = json_encode($breedData);

$jsNeuteredData = json_encode($neuteredData);
$jsRoleLabels = json_encode($roleLabels);
$jsRoleData = json_encode($roleData);
$jsAppStatusLabels = json_encode($appStatusLabels);
$jsAppStatusData = json_encode($appStatusData);

$this->registerJs("
    $(function () {
        
        // --- SECÇÃO 1: PERFORMANCE ---
        
        // Trend Chart
        new Chart($('#trendChart').get(0).getContext('2d'), {
            type: 'line',
            data: {
                labels: $jsTrendLabels,
                datasets: [
                    { 
                        label: 'Candidaturas', 
                        backgroundColor: 'rgba(60,141,188,0.1)', 
                        borderColor: '#3c8dbc', 
                        data: $jsTrendApps, 
                        fill: true 
                    },
                    { 
                        label: 'Novos Animais', 
                        backgroundColor: 'rgba(40,167,69,0.1)', 
                        borderColor: '#28a745', 
                        data: $jsTrendAni, 
                        fill: true 
                    }
                ]
            },
            options: { 
                responsive: true, 
                maintainAspectRatio: false, 
                scales: { 
                    yAxes: [{ ticks: { beginAtZero: true, stepSize: 1 } }] 
                } 
            }
        });

        // App Status Chart
        new Chart($('#appStatusChart').get(0).getContext('2d'), {
            type: 'bar',
            data: { 
                labels: $jsAppStatusLabels, 
                datasets: [{ 
                    label: 'Total', 
                    backgroundColor: '#d81b60', 
                    data: $jsAppStatusData 
                }] 
            },
            options: { 
                maintainAspectRatio: false, 
                legend: { display: false }, 
                scales: { 
                    yAxes: [{ ticks: { beginAtZero: true, stepSize: 1 } }] 
                } 
            }
        });


        // --- SECÇÃO 2: ANIMAIS ---

        // Breed Chart
        new Chart($('#breedChart').get(0).getContext('2d'), {
            type: 'bar',
            data: { 
                labels: $jsBreedLabels, 
                datasets: [{ 
                    label: 'Animais', 
                    backgroundColor: '#001f3f', 
                    data: $jsBreedData 
                }] 
            },
            options: { 
                maintainAspectRatio: false, 
                legend: { display: false }, 
                scales: { 
                    yAxes: [{ ticks: { beginAtZero: true, stepSize: 1 } }] 
                } 
            }
        });

        // Age Chart
        new Chart($('#ageChart').get(0).getContext('2d'), {
            type: 'pie',
            data: { 
                labels: $jsAgeLabels, 
                datasets: [{ 
                    data: $jsAgeData, 
                    backgroundColor: ['#f56954', '#00a65a', '#f39c12', '#00c0ef', '#3c8dbc'] 
                }] 
            },
            options: { maintainAspectRatio: false }
        });

        // Neutered Chart
        new Chart($('#neuteredChart').get(0).getContext('2d'), {
            type: 'pie',
            data: { 
                labels: ['Não Esterilizado', 'Esterilizado'], 
                datasets: [{ 
                    data: $jsNeuteredData, 
                    backgroundColor: ['#dc3545', '#3d9970'] 
                }] 
            },
            options: { maintainAspectRatio: false }
        });

        // Vaccination Chart
        new Chart($('#vacChart').get(0).getContext('2d'), {
            type: 'doughnut', 
            data: { 
                labels: ['Completa', 'Parcial', 'Não Vacinado'], 
                datasets: [{ 
                    data: $jsVacData, 
                    backgroundColor: ['#00a65a', '#f39c12', '#f56954'] 
                }] 
            },
            options: { 
                maintainAspectRatio: false, 
                legend: { position: 'right' } 
            }
        });


        // --- SECÇÃO 3: SOCIAL ---

        // Location Chart
        new Chart($('#locChart').get(0).getContext('2d'), {
            type: 'horizontalBar',
            data: { 
                labels: $jsLocLabels, 
                datasets: [{ 
                    label: 'Animais', 
                    backgroundColor: '#17a2b8', 
                    data: $jsLocData 
                }] 
            },
            options: { 
                maintainAspectRatio: false, 
                legend: { display: false }, 
                scales: { 
                    xAxes: [{ ticks: { beginAtZero: true, stepSize: 1 } }] 
                } 
            }
        });

        // Home Chart
        new Chart($('#homeChart').get(0).getContext('2d'), {
            type: 'doughnut',
            data: { 
                labels: ['Própria', 'Arrendada (Permite)', 'Arrendada (Não)'], 
                datasets: [{ 
                    data: $jsHomeData, 
                    backgroundColor: ['#28a745', '#ffc107', '#dc3545'] 
                }] 
            },
            options: { maintainAspectRatio: false, legend: { display: false } }
        });

        // Role Chart
        new Chart($('#roleChart').get(0).getContext('2d'), {
            type: 'doughnut',
            data: { 
                labels: $jsRoleLabels, 
                datasets: [{ 
                    data: $jsRoleData, 
                    backgroundColor: ['#f012be', '#39cccc', '#001f3f'] 
                }] 
            },
            options: { maintainAspectRatio: false }
        });

    });
");
?>