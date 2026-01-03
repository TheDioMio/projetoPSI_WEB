<?php
use yii\helpers\Html;

$this->title = 'Criar Tipo de Animal';
$this->params['breadcrumbs'][] = ['label' => 'Animal Types', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="animal-type-create container-fluid">
    <div class="card card-outline card-success shadow-sm">
        <div class="card-header">
            <div class="card-tools">
                <?= Html::a('<i class="fas fa-arrow-left"></i>',
                    ['index'],
                    [
                        'class' => 'btn btn-outline-secondary mr-1',
                        'title' => 'Voltar',
                    ],
                )
                ?>            </div>
        </div>
        <div class="card-body">
            <?= $this->render('_form', [
                'model' => $model,
            ]) ?>
        </div>
    </div>
</div>
