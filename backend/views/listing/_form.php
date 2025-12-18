<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Listing $listingModel */
/** @var common\models\Animal $animalModel */
/** @var yii\widgets\ActiveForm $form */
?>

    <div class="listing-form">

        <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Dados do Animal</h3>
            </div>
            <div class="card-body">

                <div class="row">
                    <div class="col-md-12">
                        <?= $form->field($animalModel, 'user_id')->dropDownList($users, ['prompt' => 'Selecione o Dono (Utilizador)...'])->label('Dono do Animal') ?>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <?= $form->field($animalModel, 'name')->textInput(['maxlength' => true]) ?>
                    </div>
                    <div class="col-md-6">
                        <?= $form->field($animalModel, 'animal_type_id')->dropDownList(
                            $animalTypes,
                            ['prompt' => 'Selecione o Tipo...', 'id' => 'animal-type']
                        ) ?>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <?= $form->field($animalModel, 'breed_id')->dropDownList(
                            [], // Preenchido via JS
                            ['prompt' => 'Selecione primeiro o tipo...', 'id' => 'breed']
                        ) ?>
                    </div>
                    <div class="col-md-6">
                        <?= $form->field($animalModel, 'age_id')->dropDownList($idades, ['prompt' => 'Selecione...']) ?>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <?= $form->field($animalModel, 'size_id')->dropDownList($portes, ['prompt' => 'Selecione...']) ?>
                    </div>
                    <div class="col-md-6">
                        <?= $form->field($animalModel, 'vaccination_id')->dropDownList($vacinas, ['prompt' => 'Selecione...']) ?>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <?= $form->field($animalModel, 'location')->textInput(['maxlength' => true]) ?>
                    </div>
                    <div class="col-md-6 pt-4">
                        <?= $form->field($animalModel, 'neutered')->checkbox() ?>
                    </div>
                </div>

                <?= $form->field($animalModel, 'description')->textarea(['rows' => 4])->label('Descrição do Animal') ?>

                <div class="form-group border p-3 bg-light rounded">
                    <label>Fotografias</label>
                    <?= $form->field($animalModel, 'imageFiles[]')->fileInput(['multiple' => true, 'accept' => 'image/*'])->label(false) ?>

                    <?php if(isset($existingImages) && count($existingImages) > 0): ?>
                        <div class="row mt-2">
                            <?php foreach($existingImages as $img): ?>
                                <div class="col-2"><img src="<?= Yii::getAlias('@frontendUrl') . $img->path ?>" class="img-thumbnail"></div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>

            </div>
        </div>

        <div class="card card-success mt-3">
            <div class="card-header">
                <h3 class="card-title">Dados do Anúncio</h3>
            </div>
            <div class="card-body">
                <?= $form->field($listingModel, 'description')->textarea(['rows' => 4])->label('Texto do Anúncio (Listing)') ?>

                <?= $form->field($listingModel, 'status')->dropDownList([
                    1 => 'Ativo',
                    0 => 'Inativo'
                ]) ?>
            </div>
        </div>

        <div class="form-group mt-3">
            <?= Html::submitButton('Guardar Tudo', ['class' => 'btn btn-success btn-lg btn-block']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

<?php
$breedsJson = json_encode($breedsByType);
$selectedBreed = (int)$animalModel->breed_id;

$script = <<< JS
    const breedsByType = $breedsJson;
    const typeSelect = document.getElementById('animal-type');
    const breedSelect = document.getElementById('breed');
    const selectedBreed = $selectedBreed;

    function updateBreeds(typeId, currentBreed = null) {
        breedSelect.innerHTML = '<option value="">Selecione a Raça...</option>';
        if (!breedsByType[typeId]) return;

        Object.entries(breedsByType[typeId]).forEach(([id, name]) => {
            const option = document.createElement('option');
            option.value = id;
            option.text = name;
            if (currentBreed && id == currentBreed) {
                option.selected = true;
            }
            breedSelect.appendChild(option);
        });
    }

    typeSelect.addEventListener('change', function () {
        updateBreeds(this.value);
    });

    if (typeSelect.value) {
        updateBreeds(typeSelect.value, selectedBreed);
    }
JS;
$this->registerJs($script);
?>