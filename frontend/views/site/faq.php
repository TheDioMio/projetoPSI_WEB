<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
// Esta linha força o carregamento do JS do Bootstrap
\yii\bootstrap4\BootstrapAsset::register($this);
\yii\bootstrap4\BootstrapPluginAsset::register($this);

$this->title = 'Perguntas Frequentes (FAQ)';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-faq">
    <div class="container py-4">
        <h1 class="mb-4 text-primary"><i class="fas fa-question-circle"></i> <?= Html::encode($this->title) ?></h1>

        <p class="lead mb-5">Encontre aqui respostas para as dúvidas mais comuns sobre a nossa plataforma de adoção.</p>

        <div class="accordion" id="accordionFAQ">

            <div class="accordion-item">
                <h2 class="accordion-header" id="headingOne">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                        1. Como posso adotar um animal?
                    </button>
                </h2>
                <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionFAQ">
                    <div class="accordion-body">
                        Para adotar um animal, deve navegar pela nossa listagem, escolher o animal que pretende e clicar no botão <strong>"Candidatar"</strong>. Deverá preencher um formulário que será enviado ao responsável pelo animal.
                    </div>
                </div>
            </div>

            <div class="accordion-item">
                <h2 class="accordion-header" id="headingTwo">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                        2. Como sei se a minha candidatura foi aceite?
                    </button>
                </h2>
                <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionFAQ">
                    <div class="accordion-body">
                        Pode acompanhar o estado das suas candidaturas na sua área de utilizador em <strong>"Minhas Candidaturas"</strong>. Além disso, receberá um e-mail de notificação assim que o responsável tomar uma decisão.
                    </div>
                </div>
            </div>

            <div class="accordion-item">
                <h2 class="accordion-header" id="headingThree">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                        3. Posso desistir de uma candidatura?
                    </button>
                </h2>
                <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#accordionFAQ">
                    <div class="accordion-body">
                        Sim. Se ainda não foi contactado ou se mudou de ideias, pode cancelar a candidatura na sua área pessoal. Recomendamos que o faça o quanto antes para não ocupar a oportunidade de outros adotantes.
                    </div>
                </div>
            </div>

            <div class="accordion-item">
                <h2 class="accordion-header" id="headingFour">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                        4. Tenho de ser maior de idade para adotar?
                    </button>
                </h2>
                <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour" data-bs-parent="#accordionFAQ">
                    <div class="accordion-body">
                        Sim, para efeitos legais de registo e responsabilidade, o utilizador que se candidata deve ter 18 anos ou mais. No caso de menores, a adoção deve ser feita pelos pais ou tutores legais.
                    </div>
                </div>
            </div>

            <div class="accordion-item">
                <h2 class="accordion-header" id="headingFive">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                        5. O que acontece depois de enviar a candidatura?
                    </button>
                </h2>
                <div id="collapseFive" class="accordion-collapse collapse" aria-labelledby="headingFive" data-bs-parent="#accordionFAQ">
                    <div class="accordion-body">
                        O responsável pelo animal (seja um particular ou uma associação) irá rever o seu perfil. Caso preencha os requisitos, entrarão em contacto para agendar uma visita e formalizar a adoção.
                    </div>
                </div>
            </div>

            <div class="accordion-item">
                <h2 class="accordion-header" id="headingSix">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSix" aria-expanded="false" aria-controls="collapseSix">
                        6. Como posso ajudar a plataforma se não puder adotar?
                    </button>
                </h2>
                <div id="collapseSix" class="accordion-collapse collapse" aria-labelledby="headingSix" data-bs-parent="#accordionFAQ">
                    <div class="accordion-body">
                        Pode ajudar imenso partilhando os animais disponíveis nas suas redes sociais! Quanto mais visibilidade um animal tiver, mais depressa encontrará um lar.
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
