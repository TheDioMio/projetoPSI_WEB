<?php

use yii\helpers\Html;
use yii\bootstrap5\ActiveForm; // Usamos o ActiveForm do Bootstrap 5
use yii\helpers\ArrayHelper;
use common\models\AnimalType;
use common\models\Breed;
use common\models\AnimalAge;
use common\models\AnimalSize;
use common\models\Vaccination;

/** @var yii\web\View $this */
/** @var common\models\Animal $model */ // O $model é um 'Animal' vazio, vindo do Controller

// --- Dropdowns vindos da BD ---
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

$this->title = 'Criar Novo Anúncio';
$this->params['breadcrumbs'][] = $this->title; // Adiciona ao "breadcrumb"
?>

<div class="container py-5">
    <div class="row g-5">

        <div class="col-lg-8">
            <div class="mb-5">
                <h1 class="text-uppercase border-start border-5 border-primary ps-3 mb-4"><?= Html::encode($this->title) ?></h1>

                <p>Este é o formulário para encontrar um novo lar para o seu animal. Preencha todos os detalhes e partilhe fotografias.</p>
            </div>

            <div class="bg-light rounded p-4 p-sm-5">

                <?php $form = ActiveForm::begin([
                    'id' => 'create-listing-form',
                    // ESSENCIAL para o upload de ficheiros
                    'options' => ['enctype' => 'multipart/form-data']
                ]); ?>

                <?= $form->field($model, 'name')->textInput(['maxlength' => true])->label('Nome do Animal') ?>

                <?= $form->field($model, 'description')->textarea(['rows' => 8])->label('História e Comportamento')
                    ->hint('Descreva o animal. É calmo? Gosta de crianças? Tem necessidades especiais?') ?>

                <hr class="my-4">

                <div class="row g-3">
                    <div class="col-md-6">
                        <?= $form->field($model, 'animal_type_id')->dropDownList(
                            $tiposDeAnimal,
                            ['prompt' => 'Selecione o Tipo...']
                        )->label('Tipo de Animal') ?>
                    </div>

                    <div class="col-md-6">
                        <?= $form->field($model, 'breed_id')->dropDownList(
                            $racas,
                            ['prompt' => 'Selecione a Raça...']
                        )->label('Raça') ?>
                    </div>
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <?= $form->field($model, 'age_id')->dropDownList(
                            $idades,
                            ['prompt' => 'Selecione a idade...']
                        )->label('Idade') ?>
                    </div>

                    <div class="col-md-6">
                        <?= $form->field($model, 'size_id')->dropDownList(
                            $portes,
                            ['prompt' => 'Selecione o Porte...', 'encode' => false]
                        )->label('Porte') ?>
                    </div>
                </div>

                <?= $form->field($model, 'location')->textInput(['maxlength' => true])
                    ->label('Localização (Distrito)')
                    ->hint('Ex: Leiria, Portugal') ?>

                <hr class="my-4">

                <div class="row g-3">
                    <div class="col-md-6">
                        <?= $form->field($model, 'vaccination_id')->dropDownList(
                            $vacinas,
                            ['prompt' => 'Estado da vacinação...']
                        )->label('Vacinas') ?>
                    </div>

                    <div class="col-md-6">
                        <?= $form->field($model, 'neutered')->checkbox()->label('Animal Esterilizado') ?>
                    </div>
                </div>

                <hr class="my-4">

                <?= $form->field($model, 'imageFiles[]')->fileInput([ // O nome é 'imageFiles[]'
                    'multiple' => true, // Permite selecionar vários ficheiros
                    'accept' => 'image/*' // Mostra só imagens no seletor
                ])
                    ->label('Fotografias do Animal (Máx. 5)')
                    ->hint('A primeira foto que escolher será a foto de capa.')
                ?>

                <div class="mt-5">
                    <?= Html::submitButton('Publicar Anúncio', [
                        'class' => 'btn btn-primary w-100 py-3 text-uppercase', // Botão grande
                        'name' => 'create-button'
                    ]) ?>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>

        <div class="col-lg-4">

            <div class="mb-5">
                <h3 class="text-uppercase border-start border-5 border-primary ps-3 mb-4">Dicas para um bom anúncio</h3>
                <div class="bg-light p-4 rounded">
                    <p class="mb-3">
                        <i class="fa fa-camera-retro text-primary me-2"></i>
                        <strong>Tire boas fotos!</strong> Use luz natural e mostre a cara e o corpo inteiro do animal.
                    </p>
                    <p class="mb-3">
                        <i class="fa fa-heart text-primary me-2"></i>
                        <strong>Seja Honesto:</strong> Descreva o temperamento real do animal, tanto o bom como o mau.
                    </p>
                    <p class="mb-0">
                        <i class="fa fa-check-circle text-primary me-2"></i>
                        <strong>Seja Claro:</strong> Informações sobre saúde e necessidades especiais são essenciais.
                    </p>
                </div>
            </div>

        </div>
    </div>
</div>