
<?php

use common\models\AnimalAge;
use common\models\AnimalSize;
use common\models\AnimalType;
use common\models\Breed;
use common\models\Vaccination;
use yii\bootstrap5\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\StringHelper;

/** @var yii\web\View $this */
/** @var \yii\data\ActiveDataProvider $provider */
/** @var \app\models\Listing[] $listings */

$this->title = 'Os Meus Anúncios';




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
<div class="container py-5">
    <h1 class="text-uppercase border-start border-5 border-primary ps-3 mb-4">
        <?= Html::encode($this->title) ?>
    </h1>
</div>

<div class="container py-5">
    <div class="row g-5">
        <div class="col-lg-8">

            <?php foreach ($listings as $listing): ?>
                <?php
                $animal = $listing->animal;
                if ($animal === null) continue;

                $primaryImage = $listing->animal->primaryImage;

                $imageUrl = $primaryImage
                    ? Yii::getAlias('@web') . '/' . $primaryImage->path
                    : Yii::getAlias('@web/img/placeholder.jpg');
                ?>

                <div class="blog-item mb-5">
                    <div class="row g-0 bg-light overflow-hidden">

                        <div class="col-12 col-sm-5 h-100">
                            <img class="img-fluid h-100"
                                 src="<?= Html::encode($imageUrl) ?>"
                                 style="object-fit: cover;">
                        </div>

                        <div class="col-12 col-sm-7 h-100 d-flex flex-column justify-content-center">
                            <div class="p-4">

                                <div class="d-flex mb-3">
                                    <small class="me-3">
                                        <i class="bi bi-bookmarks me-2"></i>
                                        <?= Html::encode($animal->animalType->description) ?>
                                    </small>

                                    <small>
                                        <i class="bi bi-calendar-date me-2"></i>
                                        <?= Yii::$app->formatter->asDate($listing->created_at, 'long') ?>
                                    </small>
                                </div>

                                <h5 class="text-uppercase mb-3"><?= Html::encode($animal->name) ?></h5>
                                <p class="card-text">
                                    <strong>Raça:</strong> <?= $listing->animal->breed->description ?? 'Sem informação' ?><br>
                                    <strong>Idade:</strong> <?= $listing->animal->animalAge->description ?? 'Sem informação' ?>
                                </p>
                                <p>
                                    <?= Html::encode(StringHelper::truncate($animal->description, 100, '...')) ?>
                                </p>

                                <!-- BOTÕES -->
                                <?= Html::a(
                                    'Editar <i class="bi bi-pencil"></i>',
                                    ['/listings/update', 'id' => $listing->id],
                                    ['class' => 'btn btn-warning btn-sm me-2']
                                ) ?>

                                <?= Html::a(
                                    'Apagar <i class="bi bi-trash"></i>',
                                    ['/listings/delete', 'id' => $listing->id],
                                    [
                                        'class' => 'btn btn-danger btn-sm',
                                        'data-confirm' => 'Tem a certeza que quer apagar este anúncio?',
                                        'data-method' => 'post',
                                    ]
                                ) ?>

                                <?= Html::a(
                                    'Ver Detalhe <i class="bi bi-chevron-right"></i>',
                                    ['/listings/detail', 'id' => $animal->id],
                                    ['class' => 'btn btn-primary btn-sm']
                                ) ?>

                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>

            <!-- PAGINAÇÃO -->
            <div class="col-12">
                <nav aria-label="Page navigation">
                    <?= \yii\widgets\LinkPager::widget([
                        'pagination' => $provider->pagination,
                        'options' => ['class' => 'pagination pagination-lg m-0'],
                        'linkContainerOptions' => ['class' => 'page-item'],
                        'linkOptions' => ['class' => 'page-link'],
                        'prevPageLabel' => '<i class="bi bi-arrow-left"></i>',
                        'nextPageLabel' => '<i class="bi bi-arrow-right"></i>',
                    ]) ?>
                </nav>
            </div>

        </div>

        <!-- SIDEBAR -->
        <div class="col-lg-4">
            <!-- Filters Start -->

                <div class="mb-5">
                    <h3 class="text-uppercase border-start border-5 border-primary ps-3 mb-4"><?=Html::encode('Filtros')?></h3>

                    <?php
                    // Inicia o ActiveForm, associando-o ao searchModel
                    // Método GET e a ação aponta para a mesma página
                    $form = ActiveForm::begin([
                        'method' => 'get',
                        'action' => ['listings/my-listings'],
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
                            ['listings/my-listings'], // URL base sem parâmetros
                            ['class' => 'h6 text-danger']
                        ) ?>
                    </div>
                </div>


            <!-- Filters End -->
        </div>
    </div>
</div>
