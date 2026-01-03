<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Animal $model */
/** @var yii\widgets\ActiveForm $form */
$currentBaseUrl = Url::base();
$frontendBaseUrl = str_replace('/backend/web', '/frontend/web', $currentBaseUrl);
?>

<div class="animal-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title"><?=Html::encode('Dados do Animal')?></h3>
        </div>
        <div class="card-body">

            <div class="row">
                <div class="col-md-12">
                    <?= $form->field($model, 'user_id')->dropDownList($users, ['prompt' => 'Selecione o Dono (Utilizador)...'])->label('Dono do Animal') ?>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-md-6">
                    <?= $form->field($model, 'animal_type_id')->dropDownList(
                        $animalTypes,
                        ['prompt' => 'Selecione o Tipo...', 'id' => 'animal-type']
                    ) ?>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <?= $form->field($model, 'breed_id')->dropDownList(
                        [], // Preenchido via JS
                        ['prompt' => 'Selecione primeiro o tipo...', 'id' => 'breed']
                    ) ?>
                </div>
                <div class="col-md-6">
                    <?= $form->field($model, 'age_id')->dropDownList($idades, ['prompt' => 'Selecione...']) ?>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <?= $form->field($model, 'size_id')->dropDownList($portes, ['prompt' => 'Selecione...']) ?>
                </div>
                <div class="col-md-6">
                    <?= $form->field($model, 'vaccination_id')->dropDownList($vacinas, ['prompt' => 'Selecione...']) ?>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <?= $form->field($model, 'location')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-md-6 pt-4">
                    <?= $form->field($model, 'neutered')->checkbox() ?>
                </div>
            </div>

            <?= $form->field($model, 'description')->textarea(['rows' => 4])->label('Descrição do Animal') ?>

            <div class="form-group border p-3 bg-light rounded shadow-sm">
                <label class="font-weight-bold">Fotografias</label>

                <div class="mb-3">
                    <?= $form->field($model, 'imageFiles[]')->fileInput([
                        'multiple' => true,
                        'accept' => 'image/*',
                        'class' => 'form-control-file'
                    ])->label(false) ?>
                </div>

                <hr>

                <?php if(isset($existingImages) && count($existingImages) > 0): ?>
                    <label class="mb-2">Imagens Atuais:</label>
                    <div class="row">
                        <?php foreach($existingImages as $img): ?>
                            <?php
                            // Lógica do URL
                            $imageSrc = $frontendBaseUrl . $img->path;
                            ?>

                            <div class="col-6 col-md-3 col-lg-2 mb-3">
                                <div class="card h-100 shadow-sm">
                                    <div class="card-body p-1 d-flex align-items-center justify-content-center bg-white" style="height: 120px;">
                                        <?= Html::img($imageSrc, [
                                            'class' => 'img-fluid',
                                            'style' => 'max-height: 100%; object-fit: contain;',
                                        ]) ?>
                                    </div>

                                    <div class="card-footer p-2 text-center bg-light border-0">
                                        <?= Html::a('<i class="fas fa-trash"></i>',
                                            ['delete-image', 'id' => $img->id, 'animal_id' => $model->id],
                                            [
                                                'class' => 'btn btn-danger btn-xs btn-block',
                                                'data' => [
                                                    'confirm' => 'Tem a certeza que pretende apagar esta imagem?',
                                                    'method' => 'post',
                                                ],
                                            ])
                                        ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p class="text-muted font-italic">Não existem fotografias carregadas.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="form-group">
        <?= Html::submitButton('Guardar', ['class' => 'btn btn-success']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>

<?php
$breedsJson = json_encode($breedsByType);
$selectedBreed = (int)$model->breed_id;

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
