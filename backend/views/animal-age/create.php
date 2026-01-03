<?php
use yii\helpers\Html;

$this->title = 'Criar Idade';
$this->params['breadcrumbs'][] = ['label' => 'Animal Ages', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="animal-age-create container-fluid">
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
                ?>
            </div>
        </div>
        <div class="card-body">
            <?= $this->render('_form', [
                'model' => $model,
            ]) ?>
        </div>
    </div>
</div>
