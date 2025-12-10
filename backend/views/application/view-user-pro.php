<?php
use yii\helpers\Html;
use yii\web\YiiAsset;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\Application $model */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Applications', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
YiiAsset::register($this);
?>
<div class="application-view">
    <div class="card card-outline card-primary shadow-sm">
        <div class="card-header">
            <div class="card-tools float-right">
                <?= Html::a('<i class="fas fa-arrow-left"></i> Voltar à Lista', ['index'], ['class' => 'btn btn-default btn-sm']) ?>
                <?= Html::a('Negar', ['deny-application', 'id' => $model->id], [
                    'class' => 'btn btn-danger btn-sm',
                    'data' => [
                        'confirm' => 'Tem a certeza que deseja negar esta candidatura?',
                        'method' => 'post',
                    ],
                ]) ?>
                <?= Html::a('Aceitar', ['accept-application', 'id' => $model->id], [
                    'class' => 'btn btn-success btn-sm',
                    'data' => [
                        'confirm' => 'Tem a certeza que deseja aceitar esta candidatura?',
                        'method' => 'post',
                    ],
                ]) ?>
            </div>
        </div>
        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                'id',
                'status', //formatar isto para mostrar "Pendente", em vez de 0
                'description',
                [
                    'attribute' => 'data',
                    'label' => 'Detalhes da Candidatura',
                    'format' => 'html',
                    'value' => function ($model) {
                        $data = $model->data;
                        //1.º -> Se não for array (estiver vazio ou null), retorna mensagem
                        if (!is_array($data)) {
                            return '<span class="text-muted">(Sem dados adicionais)</span>';
                        }
                        //2.º -> Cria uma tabela simples para mostrar os dados
                        $html = '<table class="table table-sm table-bordered" style="background: transparent;">';

                        //3.º -> Faz um foreach para todos os modelos na data, e dá display 1 por 1
                        foreach ($data as $key => $value) {
                            // Formata a chave (ex: "professional_name" -> "Professional Name")
                            $label = ucwords(str_replace('_', ' ', $key));

                            $displayValue = htmlspecialchars((string)$value);

                            $html .= "<tr><th style='width: 30%;'>{$label}</th><td>{$displayValue}</td></tr>";
                        }
                        $html .= '</table>';
                        return $html;
                    },
                ],
                'created_at:datetime',
                [
                    'label' => 'Candidato',
                    'attribute' => 'user_id',
                    'value' => function($model) {
                        return $model->user->username ?? 'Desconhecido';
                    }
                ],
            ],
        ]) ?>
    </div>
</div>