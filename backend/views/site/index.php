<?php
use hail812\adminlte\widgets\SmallBox;
use hail812\adminlte3\assets\AdminLteAsset;
use yii\helpers\Url;
use yii\bootstrap5\Html;

$this->title = 'Dashboard';
$this->params['breadcrumbs'] = [['label' => $this->title]];
$this->registerCssFile('@web/css/style.css', [
    'depends' => [AdminLteAsset::class],
]);
?>

<div class="container-fluid">
<!--    Esta row é para os widgets verdes em cima-->
    <div class="row">
        <div class="col-lg-3 col-6">
            <?= SmallBox::widget([
                'title' => count($utilizadores),
                'text' => 'Utilizadores',
                'icon' => 'fas fa-user',
                'theme' => 'gradient-success',
                'linkUrl' => Url::to(['user/index']),
            ]) ?>
        </div>
        <div class="col-lg-3 col-6">
            <?= SmallBox::widget([
                'title' => count($animais),
                'text' => 'Animais',
                'icon' => 'fas fa-paw',
                'theme' => 'gradient-success',
                'linkUrl' => Url::to(['animal/index']),
            ]) ?>
        </div>
        <div class="col-lg-3 col-6">
            <?= SmallBox::widget([
                'title' => count($listagens),
                'text' => 'Listagens',
                'icon' => 'fas fa-list',
                'theme' => 'gradient-success',
                'linkUrl' => Url::to(['listing/index']),
            ]) ?>
        </div>
        <div class="col-lg-3 col-6">
            <?= SmallBox::widget([
                'title' => count($candidaturas),
                'text' => 'Candidaturas',
                'icon' => 'fas fa-file-contract',
                'theme' => 'gradient-success',
                'linkUrl' => Url::to(['application/index']),
            ]) ?>
        </div>
    </div>

    <div class="row mt-2">
        <div class="col-lg-8">
            <div class="card card-primary card-outline">
                <div class="card-header border-0">
                    <h3 class="card-title"><i class="fas fa-history mr-1"></i><?=Html::encode('Últimos Animais Adicionados')?></h3>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-striped table-valign-middle">
                        <thead>
                        <tr>
                            <th><?=Html::encode('Nome')?></th>
                            <th><?=Html::encode('Tipo')?></th>
                            <th><?=Html::encode('Data Registo')?></th>
                            <th class="text-right"><?=Html::encode('Ver')?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if (!empty($animaisRecentes)):
                             foreach ($animaisRecentes as $animal): ?>
                                <tr>
                                    <td>
                                        <?= Html::encode($animal->name)?>
                                    </td>
                                    <td>
                                        <?= Html::encode($animal->animalType->description ?? 'N/A') ?>
                                    </td>
                                    <td>
                                        <small class="text-muted">
                                            <i class="far fa-clock mr-1"></i>
                                        </small>
                                        <?= Yii::$app->formatter->asDate($animal->created_at ?? 'now', 'php:d/m/Y') ?>
                                    </td>
                                    <td class="text-right">
                                        <?= Html::a('<i class="fas fa-search"></i>',
                                            ['/animal/view', 'id' => $animal->id],
                                            ['class' => 'text-muted', 'title' => 'Detalhes'])
                                        ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td class="text-center"><?=Html::encode('Sem animais recentes!')?></td>
                            </tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

<!--            Este card é o card que mostra as percentagens de animais na plataforma-->
            <div class="card">
                <div class="card-header border-0">
                    <h3 class="card-title"><i class="fas fa-chart-pie mr-2"></i><?=Html::encode('Distribuição dos Animais')?></h3>
                </div>
                <div class="card-body">
                    <!--Imagem e %s em decimais cães-->
                    <div class="d-flex justify-content-between align-items-center mb-0">
                        <p class="text-primary text-xl">
                            <i class="fas fa-dog"></i>
                        </p>
                        <p class="d-flex flex-column text-right">
                            <span class="font-weight-bold">
                                <?= number_format($percentagemCaes) ?>%
                            </span>
                            <span class="text-muted"><?=Html::encode('Cães')?></span>
                        </p>
                    </div>
                    <!--Barra de percentagem cães-->
                    <div class="progress mb-5">
                        <div class="progress-bar bg-primary" role="progressbar"
                             style="width: <?= $percentagemCaes ?>%"
                             aria-valuenow="<?= $percentagemCaes ?>" aria-valuemin="0" aria-valuemax="100">
                        </div>
                    </div>

                    <!--Imagem e %s em decimais gatos-->
                    <div class="d-flex justify-content-between align-items-center mb-0">
                        <p class="text-orange text-xl">
                            <i class="fas fa-cat"></i>
                        </p>
                         <p class="d-flex flex-column text-right">
                            <span class="font-weight-bold">
                                <?= number_format($percentagemGatos)?>%
                            </span>
                            <span class="text-muted"><?=Html::encode('Gatos')?></span>
                        </p>
                    </div>
                    <!--Barra de percentagem gatos-->
                    <div class="progress mb-5">
                        <div class="progress-bar bg-orange" role="progressbar"
                             style="width: <?= $percentagemGatos ?>%"
                             aria-valuenow="<?= $percentagemGatos ?>" aria-valuemin="0" aria-valuemax="100">
                        </div>
                    </div>

                    <!--Imagem e %s em decimais outros-->
                    <div class="d-flex justify-content-between align-items-center mb-0">
                        <p class="text-indigo text-xl">
                            <i class="fas fa-fish"></i>
                        </p>
                        <p class="d-flex flex-column text-right">
                            <span class="font-weight-bold"><?=number_format($percentagemOutros)?>%</span>
                            <span class="text-muted"><?= Html::encode('Outros') ?></span>
                        </p>
                    </div>
                    <!--Barra de percentagem outros-->
                    <div class="progress mb-5">
                        <div class="progress-bar bg-indigo" role="progressbar"
                             style="width: <?= $percentagemOutros ?>%"
                             aria-valuenow="<?= $percentagemOutros ?>" aria-valuemin="0" aria-valuemax="100">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><?=Html::encode('Ações Rápidas')?></h3>
                </div>
                <div class="card-body text-center">
                    <p class="text-muted mb-3"><?=Html::encode('O que deseja fazer?')?></p>

                    <?= Html::a('<i class="fas fa-plus"></i> Animal', ['/animal/create'], [
                        'class' => 'btn btn-app bg-success shadow-sm btn-quickactions',
                    ]) ?>

                    <?= Html::a('<i class="fas fa-user-plus"></i> User', ['/user/create'], [
                        'class' => 'btn btn-app bg-warning shadow-sm btn-quickactions',
                    ]) ?>

                    <?= Html::a('<i class="fas fa-edit"></i> Listar', ['/listing/create'], [
                        'class' => 'btn btn-app bg-info shadow-sm btn-quickactions',
                    ]) ?>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><?=Html::encode('Últimas Candidaturas')?></h3>
                </div>
                <div class="card-body p-0">
                    <ul class="products-list product-list-in-card pl-2 pr-2">
                        <?php foreach ($candidaturasPendentes as $app): ?>
                            <li class="item">
                                <div class="product-img">
                                    <div class="float-left p-2 bg-light rounded">
                                        <i class="fas fa-file-alt fa-2x text-secondary"></i>
                                    </div>
                                </div>
                                <div class="product-info">
                                    <a href="<?= Url::to(['/application/view', 'id' => $app->id]) ?>"
                                       class="product-title">
                                        ID #<?= $app->id ?>
                                        <span class="badge badge-warning float-right"><?=Html::encode('Pendente')?></span>
                                    </a>
                                    <span class="product-description">
                                        <?=Html::encode('Interesse em: ')?><strong><?= Html::encode($app->animal->name ?? 'Animal Desconhecido') ?></strong>
                                        <br>
                                        <small><?=Html::encode('Enviado há ') . Yii::$app->formatter->asRelativeTime($app->created_at) ?></small>
                                    </span>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <div class="card-footer text-center">
                    <a href="<?=Url::to(['/application/index']) ?>" class="uppercase"><?=Html::encode('Ver Todas as Candidaturas')?></a>
                </div>
            </div>
        </div>
    </div>
</div>