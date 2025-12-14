<?php

use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;

/* @var $this yii\web\View */
/* @var $model yii\base\DynamicModel */

$this->title = 'Candidatura Profissional';

//  ARRAYS DE DADOS
$areasAtuacao = [
    1 => 'Clínica Veterinária',
    2 => 'Canil / Abrigo',
    3 => 'Outro',
];

$anosExperiencia = [
    1 => 'Menos de 1 ano',
    2 => 'Entre 1 a 3 anos',
    3 => 'Entre 3 a 5 anos',
    4 => 'Mais de 5 anos',
];

$disponibilidade = [
    1 => 'Tempo Inteiro (Comercial)',
    2 => 'Part-time',
    3 => 'Apenas Fins de Semana',
    4 => 'Apenas por Marcação',
];
?>

<div class="container py-5">
    <h1 class="text-uppercase border-start border-5 border-primary ps-3 mb-4">
        <?= Html::encode($this->title) ?>
    </h1>
    <p class="lead text-muted">
        <?= Html::encode('Junte-se à nossa rede de parceiros certificados.') ?>
    </p>
</div>

<div class="container mb-5">
    <div class="row g-5">
        <div class="col-lg-8">
            <div class="bg-light rounded p-4 p-sm-5 shadow-sm">

                <div class="mb-4">
                    <h3 class="fw-bold"><?=Html::encode('Ficha de Profissional')?></h3>
                    <p class="text-muted small"><?=Html::encode('Todos os campos marcados são obrigatórios. 
                    A sua candidatura será analisada pela nossa equipa o mais brevemente possível')?></p>
                </div>

                <?php $form = ActiveForm::begin([
                    'id' => 'userpro-apply-form',
                ]); ?>

                <h5 class="text-primary border-bottom pb-2 mb-3 mt-4">
                    <i class="fas fa-id-card me-2"></i><?=Html::encode('Dados Profissionais')?>
                </h5>

                <div class="row">
                    <div class="col-md-8">
                        <?= $form->field($model, 'professional_name')->textInput([
                            'placeholder' => 'Ex: Clínica Vet Leiria ou João Silva Grooming'
                        ])->label('Nome do Profissional ou Empresa*') ?>
                    </div>
                    <div class="col-md-4">
                        <?= $form->field($model, 'nif')->textInput([
                            'type' => 'number',
                            'placeholder' => '123456789'
                        ])->label('NIF/NIPC*') ?>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <?= $form->field($model, 'area_id')->dropDownList($areasAtuacao, [
                            'prompt' => 'Selecione a área de atuação...'
                        ])->label('Área Principal*') ?>
                    </div>
                    <div class="col-md-6">
                        <?= $form->field($model, 'experience_level')->dropDownList($anosExperiencia, [
                            'prompt' => 'Selecione o tempo de experiência...'
                        ])->label('Experiência na Área*') ?>
                    </div>
                </div>

                <?= $form->field($model, 'website')->textInput([
                    'placeholder' => 'https://www.oseusite.com ou LinkedIn'
                ])->label('Website ou Redes Sociais') ?>

                <h5 class="text-primary border-bottom pb-2 mb-3 mt-5">
                    <i class="fas fa-briefcase me-2"></i><?=Html::encode('Detalhes do Serviço')?>
                </h5>

                <?= $form->field($model, 'availability')->radioList($disponibilidade, [
                    'item' => function($index, $label, $name, $checked, $value) {
                        $check = $checked ? 'checked' : '';
                        $active = $checked ? 'active' : '';
                        // Estilo botões para os radios
                        return "
                        <div class='form-check mb-2'>
                            <input class='form-check-input' type='radio' name='$name' id='radio_$index' value='$value' $check>
                            <label class='form-check-label' for='radio_$index'>$label</label>
                        </div>";
                    }
                ])->label('Disponibilidade Habitual') ?>

                <?= $form->field($model, 'bio')->textarea([
                    'rows' => 5,
                    'placeholder' => 'Descreva os seus serviços, a sua paixão pelos animais e o que o distingue...'
                ])->label('Apresentação / Biografia') ?>

                <hr class="my-4">

                <div class="form-check mb-4">
                    <input class="form-check-input" type="checkbox" id="termsCheck" required>
                    <label class="form-check-label small text-muted" for="termsCheck">
                        <?=Html::encode('Declaro que todas as informações prestadas são verdadeiras e aceito a ')?>
                        <?=Html::a('Política de Privacidade', ['#'])?> <?=Html::encode(' e os ')?>
                        <?=Html::a('Termos para Parceiros', ['#'])?>
                    </label>
                </div>

                <div class="form-group d-grid gap-2">
                    <?= Html::submitButton('Submeter Candidatura <i class="bi bi-send-fill ms-2"></i>', [
                        'class' => 'btn btn-primary btn-lg py-3 text-uppercase fw-bold shadow-sm'
                    ]) ?>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="position-sticky" style="top: 2rem;">

<!--                <div class="card border-0 shadow-sm mb-4 bg-primary text-white">-->
<!--                    <div class="card-body p-4">-->
<!--                        <h4 class="card-title fw-bold mb-3">-->
<!--                            <i class="fas fa-star me-2"></i>--><?php //=Html::encode('Obtém com o User Pro:')?>
<!--                        </h4>-->
<!--                        <ul class="list-unstyled mb-0">-->
<!--                            <li class="mb-2"><i class="fas fa-check-circle me-2"></i> Selo de Verificação</li>-->
<!--                            <li class="mb-2"><i class="fas fa-check-circle me-2"></i> Destaque nas Pesquisas</li>-->
<!--                            <li class="mb-2"><i class="fas fa-check-circle me-2"></i> Acesso a Estatísticas</li>-->
<!--                            <li class="mb-0"><i class="fas fa-check-circle me-2"></i> Suporte Prioritário</li>-->
<!--                        </ul>-->
<!--                    </div>-->
<!--                </div>-->

                <div class="card border-0 shadow-sm mb-4 bg-light">
                    <div class="card-body p-4">
                        <h5 class="card-title fw-bold text-uppercase text-muted mb-3"><?=Html::encode('Como funciona?')?></h5>
                        <div class="d-flex mb-3">
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-0 fw-bold"><?=Html::encode('Candidatura')?></h6>
                                <small class="text-muted"><?=Html::encode('Preencha o formulário e clique em submeter')?></small>
                            </div>
                        </div>

                        <div class="d-flex mb-3">
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-0 fw-bold"><?=Html::encode('Validação')?></h6>
                                <small class="text-muted"><?=Html::encode('A nossa equipa analisa o seu perfil')?></small>
                            </div>
                        </div>

                        <div class="d-flex">
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-0 fw-bold"><?=Html::encode('Aprovação')?></h6>
                                <small class="text-muted"><?=Html::encode('Receba status de User Pro e comece a anunciar!')?></small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm bg-light">
                    <div class="card-body p-4 text-center">
                        <i class="fas fa-headset fa-2x text-primary mb-3"></i>
                        <h6 class="fw-bold"><?=Html::encode('Precisa de Ajuda?')?></h6>
                        <p class="small text-muted mb-3"><?=Html::encode('Não hesite a nos contactar')?></p>
                        <?= Html::a(
                            'Contactar Suporte',
                            ['/site/contact'],
                            [
                                'class' => 'btn btn-outline-primary btn-sm rounded-pill px-4',
                            ]
                        ) ?>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>