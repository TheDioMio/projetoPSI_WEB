<?php
use yii\bootstrap5\Html;
use yii\grid\GridView;

/** @var \yii\web\View $this */
/** @var \common\models\User $user */
?>

<div class="card mb-3">
    <div class="card-header d-flex justify-content-between align-items-center">
        <strong>Atividade recente</strong>
        <?= Html::a('Ver todos os anúncios', ['listings/my-listings'], ['class' => 'btn btn-sm btn-outline-primary']) ?>
    </div>
    <div class="card-body">
        <p class="text-muted small">Aqui podes listar os teus anúncios, candidaturas, comentários, etc.</p>

        <!-- Exemplo: GridView com anúncios do utilizador (ajusta o dataProvider no controller) -->
        <?php // echo GridView::widget([...]); ?>
    </div>
</div>
