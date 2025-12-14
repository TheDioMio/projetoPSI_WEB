<?php

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Meus Favoritos';
?>

    <div class="container py-5">
        <div class="text-center mb-5">
            <h1 class="display-4 mb-3"><?=Html::encode('Favoritos')?><i class="bi bi-heart-fill text-danger"></i></h1>
            <p class="lead text-muted"><?=Html::encode('Aqui estão os animais que guardou.')?></p>
        </div>

        <div id="empty-state" class="text-center d-none py-5">
            <div class="mb-4">
                <i class="bi bi-emoji-frown" style="font-size: 4rem; color: #ccc;"></i>
            </div>
            <h3><?=Html::encode('Ainda não tem favoritos!')?></h3>
            <p class="mb-4"><?=Html::encode('Volte à lista para encontrar o seu companheiro ideal.')?></p>
            <?=Html::a(
                'Ver Animais',
                ['listings/animal'],
                ['class' => 'btn btn-primary px-4 py-2'],
            );
            ?>
        </div>

        <div id="favorites-container" class="row g-4">
        </div>
    </div>

    <div id="card-template" class="d-none">
        <div class="col-12 col-md-6 col-lg-4 card-item">
            <div class="card h-100 shadow-sm border-0 overflow-hidden">
                <div class="position-relative">
                    <img class="card-img-top img-animal" src="" alt="Animal" style="height: 250px; object-fit: cover;">
                    <div class="position-absolute top-0 end-0 p-2">
                        <button class="btn btn-light btn-sm rounded-circle shadow-sm btn-remover" title="Remover dos favoritos">
                            <i class="bi bi-trash text-danger"></i>
                        </button>
                    </div>
                </div>

                <div class="card-body text-center">
                    <h4 class="card-title fw-bold text-uppercase nome-animal mb-3"><?=Html::encode('Nome')?></h4>

                    <div class="d-flex justify-content-center gap-3 mb-4 text-muted small">
                        <div>
                            <i class="bi bi-tag-fill text-primary"></i> <span class="raca-animal"><?=Html::encode('Raça')?></span>
                        </div>
                        <div>
                            <i class="bi bi-calendar-event-fill text-primary"></i> <span class="idade-animal"><?=Html::encode('Idade')?></span>
                        </div>
                    </div>

                    <div class="d-grid">
                        <?= Html::a(
                            'VER DETALHES <i class="bi bi-chevron-right"></i>',
                            '#', ['class' => 'btn btn-outline-primary btn-detalhes',]
                        )
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>




<?php
// JAVASCRIPT PARA LER E MOSTRAR OS DADOS, TUDO EXPLICADO PASSO A PASSO
$script = <<< JS
$(document).ready(function() {
    //constante, chave de storage dos favoritos
    const STORAGE_KEY = 'myFavourites';
    
    //1. Ler o LocalStorage, se não existir cria uma lista vazia
    let favoritos = JSON.parse(localStorage.getItem(STORAGE_KEY)) || [];
    
    //Formas para embelezar os dados: estão vazios na view, mas vão ser preenchidas pelo JS
    let container = $('#favorites-container');
    let template = $('#card-template').children();

    //Função para atualizar a vista
    function render() {
        container.empty(); // Limpa o conteúdo no container, antes de o preencher
        
        //Se não existirem favoritos, remove o d-none (classe que o oculta normalmente)
        if (favoritos.length === 0) {
            $('#empty-state').removeClass('d-none');
            return;
        }

        //Esconde a mensagem de vazio se houver itens
        $('#empty-state').addClass('d-none');

        //Loop para criar os cartões
        favoritos.forEach(function(animal) {
            let clone = template.clone();

            // Preencher os dados no HTML (clonamos da template em cima)
            clone.find('.nome-animal').text(animal.name);
            clone.find('.img-animal').attr('src', animal.image);
            clone.find('.raca-animal').text(animal.breed);
            clone.find('.idade-animal').text(animal.age);
            clone.find('.btn-detalhes').attr('href', animal.link);

            //Função do botão de remover
            clone.find('.btn-remover').on('click', function() {
                if(confirm('Tem a certeza que quer remover este favorito?')) {
                    //1. Lemos a lista ATUAL do localStorage (para garantir que está atualizada)
                    let listaAtual = JSON.parse(localStorage.getItem(STORAGE_KEY)) || [];
                    
                    //2. Cria uma nova lista, onde filtra pelo elemento que o user quer remover, removendo-o da lista
                    let novaLista = listaAtual.filter(function(item) {
                        return String(item.id) !== String(animal.id);
                    });

                    //3. Guardamos a nova lista
                    localStorage.setItem(STORAGE_KEY, JSON.stringify(novaLista));
                    
                    //4. Removemos o cartão do ecrã
                    clone.fadeOut(300, function() {
                        $(this).remove();
                        // Se não sobrar nada, mostramos a mensagem de vazio
                        if (novaLista.length === 0) {
                            $('#empty-state').removeClass('d-none');
                        }
                    });
                }
            });

            // Adicionar à página
            container.append(clone);
        });
    }
    //Este render inicia tudo. Sem ele nada funciona
    render();
});
JS;
$this->registerJs($script);
?>