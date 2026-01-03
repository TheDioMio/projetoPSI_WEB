<?php
use yii\helpers\Html;
use yii\web\YiiAsset;
use yii\widgets\DetailView;

$this->title = 'Detalhes do Status de Vacinação #' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Vaccinations', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
YiiAsset::register($this);
?>
<div class="vaccination-view">
    <div class="card card-outline card-primary shadow-sm">
        <div class="card-header">
            <div class="card-tools float-right">
                <?= Html::a('<i class="fas fa-arrow-left"></i>', ['index'], ['class' => 'btn btn-default btn-sm']) ?>
                <?= Html::a('Delete', ['delete', 'id' => $model->id], [
                    'class' => 'btn btn-danger btn-sm',
                    'data' => [
                        'confirm' => 'Tem a certeza que deseja eliminar este status de vacinação?',
                        'method' => 'post',
                    ],
                ]) ?>
            </div>
        </div>
        <div class="card-body">
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'id',
                    [
                        'label' => 'Descrição',
                        'attribute' => 'description',
                    ],
                ],
            ]) ?>
        </div>
    </div>
</div>
