<?php
use yii\helpers\Html;

$this->title = 'Sobre Nós';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="site-sobreNos py-5">
    <div class="container">
        <h1 class="title-sobreNos text-center mb-5">
            Sobre Nós
        </h1>

        <div class="row align-items-center">
            <div class="col-md-6 mb-4 mb-md-0">
                <?= Html::img('@web/img/imgAbout.png', [
                    'alt' => 'PetPanion About Us',
                    'class' => 'img-fluid rounded shadow-lg'
                ]) ?>
            </div>

            <div class="col-md-6">
                <div class="card-sobreNos p-4 rounded-4 shadow-lg">
                    <h2 class="mb-3">Bem-vindo à PetPanion</h2>
                    <p>
                        A <strong>PetPanion</strong> é uma plataforma dedicada à adoção responsável de animais,
                        criada com o objetivo de aproximar quem quer adotar e quem precisa de encontrar
                        um novo lar para os seus companheiros de quatro patas.
                    </p>
                    <p>
                        Acreditamos que cada animal merece uma segunda oportunidade e uma família que lhe ofereça
                        amor, segurança e carinho. Trabalhamos com associações para garantir que cada adoção é feita de
                        forma ética, segura e transparente.
                    </p>
                    <p>
                        O nosso compromisso é com o <strong>bem-estar animal</strong> ao ajudá-los a encontrar
                        o seu <strong>lar para sempre</strong> e ao dar apoio a quem os acolhe neste processo.
                    </p>

                    <h3 class="mt-4">A nossa missão</h3>
                    <ul class="lista-missao">
                        <li>Promover a adoção responsável de animais abandonados;</li>
                        <li>Apoiar associações e protetores locais;</li>
                        <li>Educar a comunidade sobre o respeito e cuidado pelos animais;</li>
                        <li>Reduzir o número de animais em situação de rua.</li>
                    </ul>

                    <p class="quote-sobreNos mt-4">
                        <em>“Adotar é multiplicar o amor e mudar duas vidas de uma só vez.”</em>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>