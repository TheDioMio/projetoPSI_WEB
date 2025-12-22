<?php

use common\models\AnimalAge;
use common\models\AnimalSize;
use common\models\AnimalType;
use common\models\Breed;
use common\models\Vaccination;
use yii\bootstrap5\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\StringHelper;
use yii\widgets\LinkPager;

/** @var yii\web\View $this */

$this->title = 'Animais';

$videoUrl = Yii::getAlias('@web/video/banner1.mp4');
$posterUrl = Yii::getAlias('@web/video/banner1.png');

$tiposDeAnimal = ArrayHelper::map(
    AnimalType::find()->orderBy(['description' => SORT_ASC])->all(),
    'id',
    'description'
);

$racas = ArrayHelper::map(
    Breed::find()->orderBy(['description' => SORT_ASC])->all(),
    'id',
    'description'
);

$idades = ArrayHelper::map(
    AnimalAge::find()->orderBy(['description' => SORT_ASC])->all(),
    'id',
    'description'
);

$portes = ArrayHelper::map(
    AnimalSize::find()->orderBy(['id' => SORT_ASC])->all(),
    'id',
    'description'
);

$vacinas = ArrayHelper::map(
    Vaccination::find()->orderBy(['id' => SORT_ASC])->all(),
    'id',
    'description'
);

?>

<?php $this->beginBlock('hero'); ?>

<div class="container-fluid p-0 hero-video-banner">

    <video poster="<?= Html::encode($posterUrl) ?>" autoplay loop muted playsinline>
        <source src="<?= Html::encode($videoUrl) ?>" type="video/mp4">
        O seu browser não suporta vídeos HTML5.
    </video>

    <div class="banner-content container text-center">
        <h1 class="display-3 text-white mb-4">
            <?=Html::encode('Encontre aqui o seu próximo companheiro')?>
        </h1>
        <p class="lead text-white-50">
            <?=Html::encode('Procure cães, gatos e outros animais que precisam de um lar.')?>
            Procure cães, gatos e outros animais que precisam de um lar.
        </p>
    </div>
</div>

<?php $this->endBlock(); ?>

<!-- Blog Start -->
<div class="container py-5">
    <div class="row g-5">
        <!-- Blog list Start -->
        <div class="col-lg-8">

            <?php foreach ($dataProvider->getModels() as $listing): ?>
                <?php
                // Para ser mais fácil, vamos buscar o animal que está "dentro" do anúncio
                $animal = $listing->animal;

                // Se, por alguma razão, o animal deste anúncio foi apagado,
                // saltamos este loop e passamos ao próximo
                if ($animal === null) {
                    continue;
                }

                $primaryImage = $listing->animal->primaryImage;

                $imageUrl = isset($primaryImage)
                    ? Yii::getAlias('@web') . '/' . $primaryImage->path
                    : Yii::getAlias('@web/img/placeholder.jpg');

                $detailUrl = Url::to(['/listings/detail', 'id' => $animal->id]);
                ?>

                <div class="blog-item mb-5">
                    <div class="row g-0 bg-light overflow-hidden">
                        <div class="col-12 col-sm-5 h-100">
                            <img class="img-fluid h-100" src="<?= Html::encode($imageUrl) ?>" style="object-fit: cover;">
                        </div>
                        <div class="col-12 col-sm-7 h-100 d-flex flex-column justify-content-center">
                            <div class="p-4">
                                <div class="d-flex mb-3 align-items-center">
                                    <small class="me-3">
                                        <i class="bi bi-bookmarks me-2"></i>
                                        <?= Html::encode($animal->animalType->description) ?>
                                    </small>
                                    <small>
                                        <i class="bi bi-calendar-date me-2"></i>
                                        <?= Yii::$app->formatter->asDate($listing->created_at, 'long') ?>
                                    </small>

                                    <div class="ms-auto">
                                        <i class="bi bi-star btn-favourite text-warning"
                                           style="cursor: pointer; font-size: 1.5rem;"

                                           data-id="<?= $animal->id ?>"
                                           data-name="<?= Html::encode($animal->name) ?>"
                                           data-image="<?= $imageUrl ?>"
                                           data-breed="<?= Html::encode($animal->breed->description ?? 'Raça n/d') ?>"
                                           data-age="<?= Html::encode($animal->animalAge->description ?? 'Idade n/d') ?>"
                                           data-link="<?= $detailUrl ?>"

                                           title="Guardar nos favoritos">
                                        </i>
                                    </div>
                                </div>

                                <h5 class="text-uppercase mb-3"><?php echo $animal->name ?></h5>

                                <p class="card-text">
                                    <strong>Raça:</strong> <?= $listing->animal->breed->description ?? 'Sem informação' ?><br>
                                    <strong>Idade:</strong> <?= $listing->animal->animalAge->description ?? 'Sem informação' ?>
                                </p>

                                <p>
                                    <?= Html::encode(StringHelper::truncate($animal->description, 100, '...')) ?>
                                </p>

                                <?=Html::a(
                                    'Detalhes<i class="bi bi-chevron-right"></i>',
                                    ['/listings/detail', 'id' => $animal->id],
                                    ['class' => 'text-primary text-uppercase']
                                );
                                ?>
                                <div class="text-end">
                                    <small class="me-3 ">
                                        <i class="bi bi-eye me-2"></i>
                                        <?= Html::encode($animal->listing->views) ?>
                                    </small>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?> <div class="col-12">
                <nav aria-label="Page navigation">

                    <?=LinkPager::widget([
                        'pagination' => $dataProvider->getPagination(),

                        // Classes gerais
                        'options' => [
                            'class' => 'pagination pagination-lg m-0',
                        ],
                        'linkContainerOptions' => ['class' => 'page-item'],
                        'linkOptions' => ['class' => 'page-link'],

                        'prevPageLabel' => '<i class="bi bi-arrow-left"></i>',
                        'nextPageLabel' => '<i class="bi bi-arrow-right"></i>',
                    ]) ?>

                </nav>
            </div>
        </div>
        <!-- Blog list End -->

        <!-- Sidebar Start -->
        <div class="col-lg-4">
            <!-- Search Form Start -->
            <div class="mb-5">
                <form method="get" action="<?= Url::to(['listings/animal']) ?>">
                    <div class="input-group">
                        <input type="text" class="form-control p-3" name="ListingSearch[q]" placeholder="Pesquisa">
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </form>
            </div>
            <!-- Search Form End -->

            <!-- Filters Start -->
            <div class="mb-5">
                <h3 class="text-uppercase border-start border-5 border-primary ps-3 mb-4"><?=Html::encode('Filtros')?></h3>

                <?php
                // Inicia o ActiveForm, associando-o ao searchModel
                // Método GET e a ação aponta para a mesma página
                $form = ActiveForm::begin([
                    'method' => 'get',
                    'action' => ['listings/animal'],
                    'options' => ['class' => 'd-flex flex-column justify-content-start'],
                    'enableClientValidation' => false,
                ]);
                ?>

                <div class="mb-3">
                    <label class="h5 text-primary mb-1 ps-2"><?=Html::encode('Tipo de Animal')?></label>
                    <?= $form->field($searchModel, 'animal_type_id')->dropDownList( // 💡 Note: Usa $searchModel
                        $tiposDeAnimal,
                        [
                            'prompt' => '— Qualquer Tipo —',
                            'class' => 'form-select h5 bg-light py-2 px-3',
                            'onchange' => 'this.form.submit()' // Submete automaticamente
                        ]
                    )->label(false) ?>
                </div>

                <div class="mb-3">
                    <label class="h5 text-primary mb-1 ps-2"><?=Html::encode('Raça')?></label>
                    <?= $form->field($searchModel, 'breed_id')->dropDownList( // 💡 Note: Usa $searchModel e breed_id
                        $racas,
                        [
                            'prompt' => '— Qualquer Raça —',
                            'class' => 'form-select h5 bg-light py-2 px-3',
                            'onchange' => 'this.form.submit()' // Submete automaticamente
                        ]
                    )->label(false) ?>
                </div>

                <div class="mb-3">
                    <label class="h5 text-primary mb-1 ps-2"><?=Html::encode('Idade')?></label>
                    <?= $form->field($searchModel, 'animal_age_id')->dropDownList( // 💡 Note: Usa $searchModel e animal_age_id
                        $idades,
                        [
                            'prompt' => '— Qualquer Idade —',
                            'class' => 'form-select h5 bg-light py-2 px-3',
                            'onchange' => 'this.form.submit()'
                        ]
                    )->label(false) ?>
                </div>

                <div class="mb-3">
                    <label class="h5 text-primary mb-1 ps-2"><?=Html::encode('Porte')?></label>
                    <?= $form->field($searchModel, 'animal_size_id')->dropDownList( // 💡 Note: Usa $searchModel e animal_size_id
                        $portes,
                        [
                            'prompt' => '— Qualquer Porte —',
                            'class' => 'form-select h5 bg-light py-2 px-3',
                            'onchange' => 'this.form.submit()'
                        ]
                    )->label(false) ?>
                </div>

                <?php ActiveForm::end(); ?>

                <div class="mb-3 pt-2">
                    <?= Html::a(
                        '<i class="bi bi-x-circle me-2"></i> Limpar Filtros',
                        ['listings/animal'], // URL base sem parâmetros
                        ['class' => 'h6 text-danger']
                    ) ?>
                </div>
            </div>
            <!-- Filters End -->

            <!-- Plain Text Start -->
            <?php if (Yii::$app->user->can('applyUserPro')):?>
                <div class="mb-5">
                    <h3 class="text-uppercase border-start border-5 border-primary ps-3 mb-4"><?=Html::encode('Seja nosso parceiro!')?></h3>
                    <img src="../img/blog-1.jpg" alt="" class="img-fluid rounded">
                </div>

                <div>
                    <div class="bg-light text-center" style="padding: 30px;">
                        <p><?=Html::encode('Ajude-nos na missão de arranjar abrigo e garantir a saúde dos nossos
                        amigos de patas! Clique no botão abaixo e candidate-se a parceiro.')?></p>
                        <?= Html::a(
                            'Candidatar-se agora <i class="bi bi-arrow-right"></i>',
                            ['/application/apply-user-pro'],
                            [
                                'class' => 'btn btn-primary text-white fw-bold text-uppercase rounded-pill shadow-sm',
                            ]
                        ) ?>
                    </div>
                </div>
            <?php endif;?>
            <!-- Plain Text End -->
        </div>
        <!-- Sidebar End -->
    </div>
</div>
<!-- Blog End -->

<?php
//JAVASCRIPT PARA GUARDAR OS FAVORITOS, TUDO EXPLICADO PASSO A PASSO
$script = <<< JS
    //Isto é a chave para aceder ao que está guardado no localstorage, se alguém quiser aceder aos favoritos pelo
    //localstorage, onde quer que seja, basta aceder ao "myFavourites".
    const STORAGE_KEY = 'myFavourites';

    //1. Esta função ativa quando o user clicar no botão dos favoritos (a estrela amarela)
    $('.btn-favourite').on('click', function() {
        //Liga a variável "star" ao botão EXATO que foi clicado. Há imensas estrelas, assim ele sabe qual dela foi acionada
        let star = $(this);
        
        //Vai buscar a data do animal
        let animalData = {
            id: star.data('id'),
            name: star.data('name'),
            image: star.data('image'),
            breed: star.data('breed'),
            age: star.data('age'),
            link: star.data('link')
        };

        //Vai buscar os favoritos guardados, se não existir, cria uma lista vazia, daí o "[]"
        let favorites = JSON.parse(localStorage.getItem(STORAGE_KEY)) || [];
        
        //Vai percorrer a lista de favoritos um a um, e vê se o ID do animal é igual a algum que já esteja na lista do LocalStorage
        let index = favorites.findIndex(item => item.id === animalData.id);

        //Se o animal não existir na lista, devolve -1. Guarda o animal, e muda o ícone da estrela para preencher.
        if (index === -1) {
            favorites.push(animalData);
            star.removeClass('bi-star').addClass('bi-star-fill');
            //Se o animal já existir, devolve 1. Remove o animal, e muda o ícone da estrela.
        } else {
            favorites.splice(index, 1);
            star.removeClass('bi-star-fill').addClass('bi-star');
        }
        
        //Isto guarda as alterações feitas
        localStorage.setItem(STORAGE_KEY, JSON.stringify(favorites));
    });

    // 2. Isto é para manter as estrelas "pintadas" ao recarregar a página:
    let favorites = JSON.parse(localStorage.getItem(STORAGE_KEY)) || [];
    //Vai buscar todos os favoritos ao LocalStorage
    $('.btn-favourite').each(function() {
        let id = $(this).data('id');
        if (favorites.some(item => item.id === id)) {
            $(this).removeClass('bi-star').addClass('bi-star-fill');
        }
    });
JS;
$this->registerJs($script);
?>