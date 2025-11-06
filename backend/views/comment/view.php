<?php

use yii\helpers\Html;
use yii\helpers\StringHelper;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\Comment $model */

// Vamos tornar o título um pouco mais descritivo
$this->title = 'Detalhes do Comentário #' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Comments', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Ver'; // O título da página atual
\yii\web\YiiAsset::register($this);
?>
<div class="comment-view">

    <div class="card card-outline card-primary shadow-sm">

        <div class="card-header">
            <div class="card-tools float-right">
                <?= Html::a('Delete', ['delete', 'id' => $model->id], [
                    'class' => 'btn btn-danger btn-sm', // Adicionei btn-sm para consistência
                    'data' => [
                        'confirm' => 'Tem a certeza que deseja eliminar este comentário?',
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
                        'label' => 'Animal Listado',
                        'attribute' => 'listing.animal.description',
                    ],
                    [
                        'label' => 'Autor do Comentário',
                        'attribute' => 'listing.user.username',
                    ],
                    [
                        'label' => 'Conteúdo',
                        'attribute' => 'text',
                        'format'=> 'ntext',
                        'value' => function ($model) {
                            return StringHelper::truncate($model->text, 100, '...');
                        }
                    ],
                    [
                        'label' => 'Data de Publicação',
                        'attribute' => 'created_at',
                    ],
                ]
            ]) ?>
        </div>
    </div>
</div>