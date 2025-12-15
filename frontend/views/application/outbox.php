
<?php

use common\models\Application;
use yii\grid\ActionColumn;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Candidaturas Enviadas';
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="container py-4">
    <div class="row g-4">

        <div class="application-outbox col-lg-8">

            <h1><?= Html::encode($this->title) ?></h1>

            <p class="text-end">
                <?= Html::a('Ver candidaturas recebidas', ['inbox'], ['class' => 'btn btn-primary']) ?>
            </p>

            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'rowOptions' => function ($model) {
                    $url = Url::to(['application/view', 'id' => $model->id, 'box' => 'outbox']);
                    return [
                        'onclick' => "window.location='{$url}'",
                        'class' => 'clickable-row'
                    ];
                },
                'columns' => [
                    [
                        'label' => 'Destinatário',
                        'value' => fn($model) => $model->animalOwner->name ?? '—'
                    ],
                    [
                        'label' => 'Animal',
                        'value' => fn($model) => $model->animal->name ?? '—'
                    ],
                    [
                        'label' => 'Estado',
                        'value' => fn($model) => $model->getStatusLabel()
                    ],
                    [
                        'attribute' => 'created_at',
                        'label' => 'Data'
                    ],
                    [
                        'class' => ActionColumn::className(),
                        'template' => '{view}',
                        'urlCreator' => function ($action, Application $model, $key, $index, $column) {
                            return Url::toRoute([$action, 'id' => $model->id, 'type' => "outbox"]);
                        }
                    ],
                ],
            ]); ?>


        </div>

        <!-- Sidebar Start -->
        <div class="col-lg-4">
            <div class="position-sticky" style="top: 2rem;">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-body">
                        <h5 class="card-title mb-3 d-flex align-items-center">
                            <span class="me-2">🐾</span>
                            As Minhas Candidaturas
                        </h5>

                        <p class="text-muted mb-3">
                            Nesta área pode acompanhar todas as candidaturas que submeteu.
                        </p>

                        <p class="text-muted mb-3">
                            Consulte o estado de cada pedido, reveja as informações enviadas
                            e fique atento a possíveis respostas.
                        </p>

                        <div class="alert alert-primary-subtle mb-0 small">
                            A adoção é um compromisso importante — obrigado por dar este passo
                            em direção a um novo começo.
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Sidebar End -->
    </div>
</div>