<?php

/* @var $this yii\web\View */

use yii\helpers\Html;

$this->title = 'Termos e Condições';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-terms container py-4">
    <h1 class="text-primary mb-4"><?= Html::encode($this->title) ?></h1>

    <div class="card shadow-sm">
        <div class="card-body p-4">
            <p class="text-muted">Versão 1.0 - Atualizado em: <?= date('d/m/Y') ?></p>

            <section class="mb-4">
                <h4 class="text-dark">1. Aceitação dos Termos</h4>
                <p>Ao aceder e utilizar esta plataforma de adoção, o utilizador concorda em cumprir e estar vinculado aos seguintes termos e condições de utilização.</p>
            </section>

            <section class="mb-4 border-start border-danger border-4 ps-3 bg-light py-2">
                <h4 class="text-danger fw-bold">2. Proibição de Fins Comerciais</h4>
                <p>Esta plataforma foi criada com o objetivo exclusivo de promover a <strong>adoção responsável, ética e gratuita</strong> de animais. É estritamente proibido:</p>
                <ul>
                    <li>A venda de animais ou a solicitação de qualquer valor monetário (venda, reserva ou "taxas de envio" não comprovadas) em troca da entrega do animal;</li>
                    <li>A utilização deste espaço para publicidade de serviços comerciais ou criação de animais para lucro;</li>
                    <li>Qualquer atividade que vise o benefício económico através dos animais listados.</li>
                </ul>
                <div class="alert alert-danger d-flex align-items-center mb-0" role="alert">
                    <div>
                        <strong>Aviso Importante:</strong> Qualquer utilizador detetado a utilizar a plataforma para fins comerciais, venda de animais ou exploração financeira será <strong>banido permanentemente</strong> e, se aplicável, denunciado às autoridades competentes por maus-tratos ou comércio ilegal.
                    </div>
                </div>
            </section>

            <section class="mb-4">
                <h4 class="text-dark">3. Processo de Adoção</h4>
                <p>A plataforma atua apenas como intermediária entre adotantes e responsáveis. Não garantimos a conclusão da adoção nem nos responsabilizamos pelo comportamento dos animais ou dos utilizadores após o contacto inicial.</p>
            </section>

            <section class="mb-4">
                <h4 class="text-dark">4. Propriedade Intelectual</h4>
                <p>Todo o conteúdo inserido (fotos e descrições de animais) é da responsabilidade de quem o publica. O utilizador concede à plataforma o direito de exibir esse conteúdo para fins de promoção da adoção.</p>
            </section>

            <section class="mb-4">
                <h4 class="text-dark">5. Alterações aos Termos</h4>
                <p>Reservamo-nos o direito de alterar estes termos a qualquer momento. O uso continuado do site após tais alterações constitui a aceitação dos novos termos.</p>
            </section>

            <div class="alert alert-info mt-4">
                A utilização abusiva desta plataforma resultará na suspensão imediata da conta do utilizador.
            </div>
        </div>
    </div>
</div>
