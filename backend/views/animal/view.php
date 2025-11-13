<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\Animal $model */

$this->title = 'Detalhes do animal ' . mb_strtoupper($model->name);
$this->params['breadcrumbs'][] = ['label' => 'Animals', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="animal-view">
    <div class="card card-outline card-primary shadow-sm">
        <div class="card-header">
            <div class="card-tools float-right">
                <p>
                    <?= Html::a('<i class="fas fa-arrow-left"></i> Voltar à Lista', ['index'], ['class' => 'btn btn-default btn-sm']) ?>
                    <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary btn-sm']) ?>
                    <?= Html::a('Delete', ['delete', 'id' => $model->id], [
                        'class' => 'btn btn-danger btn-sm',
                        'data' => [
                            'confirm' => 'Tem a certeza que deseja eliminar este animal?',
                            'method' => 'post',
                        ],
                    ]) ?>
                </p>
            </div>
        </div>
        <div class="card-body">
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'id',
                    'description:ntext',
//            'size',
//            'age',
//            'animal_type_id',
//            'breed_id',
//            'vaccines',
//            'neutered',
//            'location',
//            'user_id',
//            'created_at',
                ],
            ]) ?>
        </div>
    </div>
</div>

