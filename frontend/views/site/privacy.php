<?php

/* @var $this yii\web\View */

use yii\helpers\Html;

$this->title = 'Política de Privacidade';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-privacy container py-4">
    <h1 class="text-primary mb-4"><?= Html::encode($this->title) ?></h1>

    <div class="card shadow-sm">
        <div class="card-body p-4">
            <p class="text-muted">Última atualização: <?= date('d/m/Y') ?></p>

            <section class="mb-4">
                <h3 class="h5 text-dark">1. Introdução</h3>
                <p>A nossa plataforma de adoção de animais respeita a sua privacidade e compromete-se a proteger os dados pessoais que partilha connosco. Esta política descreve como recolhemos e utilizamos as suas informações.</p>
            </section>

            <section class="mb-4">
                <h3 class="h5 text-dark">2. Informações que Recolhemos</h3>
                <p>Ao registar-se ou candidatar-se a uma adoção, podemos recolher as seguintes informações:</p>
                <ul>
                    <li>Nome completo e dados de contacto (e-mail e telefone);</li>
                    <li>Informações sobre a sua habitação e experiência com animais (fornecidas nos formulários de candidatura);</li>
                    <li>Dados de registo e logs de acesso para segurança do sistema.</li>
                </ul>
            </section>

            <section class="mb-4">
                <h3 class="h5 text-dark">3. Finalidade dos Dados</h3>
                <p>Os dados recolhidos servem exclusivamente para:</p>
                <ul>
                    <li>Facilitar o processo de comunicação entre adotantes e responsáveis pelos animais;</li>
                    <li>Gerir as suas candidaturas e o seu perfil de utilizador;</li>
                    <li>Garantir a segurança e integridade da nossa comunidade.</li>
                </ul>
            </section>

            <section class="mb-4">
                <h3 class="h5 text-dark">4. Partilha de Dados</h3>
                <p>Os seus dados de contacto serão partilhados apenas com o <strong>responsável direto pelo animal</strong> ao qual se candidatou, para que este possa dar seguimento ao processo de adoção.</p>
            </section>

            <section class="mb-4">
                <h3 class="h5 text-dark">5. Os Seus Direitos</h3>
                <p>De acordo com o RGPD, tem o direito de aceder, retificar ou eliminar os seus dados pessoais a qualquer momento através das definições da sua conta ou contactando-nos através da nossa página de contacto.</p>
            </section>

            <hr>

            <p class="mb-0">Se tiver dúvidas sobre esta política, por favor entre em contacto através do nosso <a href="<?= \yii\helpers\Url::to(['site/contact']) ?>">Formulário de Contacto</a>.</p>
        </div>
    </div>
</div>
