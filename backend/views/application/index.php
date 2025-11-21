<?php

use common\models\Application;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\ApplicationSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Gestão de Candidaturas';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="application-index container-fluid">
    <div class="card card-outline card-primary shadow-sm">
        <div class="card-header">
            <div class="card-tools float-right">
<!--                Comentado porque, por agora, backend não pode criar candidaturas-->
<!--                --><?php //= Html::a('<i class="fas fa-plus-circle"></i> Criar Candidatura', ['create'], ['class' => 'btn btn-success btn-sm']) ?>
            </div>
        </div>
        <div class="card-body p-0">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'tableOptions' => ['class' => 'table table-hover table-striped table-sm'],
                'layout' => "{items}\n{summary}\n{pager}",
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    'id',
                    [
                            'label'=> 'Candidato',
                        'value'=>'candidate.name',
                        'attribute'=>'candidate_name',
                    ],
                    [
                        'label'=> 'Dono do Animal',
                        'value'=>'animalOwner.name',
                        'attribute'=>'animal_owner_name',
                    ],
                    [
                        'label'=> 'Animal',
                        'value'=>'animal.name',
                        'attribute'=>'animal_name',
                    ],
                    //'type',
                    //'created_at',
                    //'target_user_id',
                    //'data',
                    [
                        'class' => ActionColumn::className(),
                        'template' => '{view} {delete}', //Por agora, backend não pode criar nem editar candidaturas
                        'urlCreator' => function ($action, Application $model, $key, $index, $column) {
                            return Url::toRoute([$action, 'id' => $model->id]);
                        }
                    ],
                ],
            ]); ?>
        </div>
    </div>
</div>


<?php //$form = ActiveForm::begin([
//    'id' => 'apply-form',
//    'action' => ['/site/apply', 'animal_id' => $animal->id],
//    'method' => 'post',
//]); ?>
<!---->
<?php //= $form->errorSummary($model, [
//    'class' => 'alert alert-danger',
//    'header' => '<strong>Há erros no formulário:</strong>',
//]) ?>
<!---->
<!--<h4 class="mt-4 mb-3">Dados Pessoais</h4>-->
<!---->
<?php //= $form->field($model, 'data[name]')->textInput(['maxlength' => true])->label('Nome Completo') ?>
<!---->
<?php //= $form->field($model, 'data[age]')->textInput(['type' => 'number'])->label('Idade') ?>
<!---->
<?php //= $form->field($model, 'data[contact]')->textInput(['type' => 'tel'])->label('Contacto') ?>
<!---->
<!--<hr class="my-4">-->
<!---->
<!--<h4 class="mt-4 mb-3">Habitação</h4>-->
<!---->
<?php //= $form->field($model, 'data[home]')
//    ->dropDownList($home, ['prompt' => 'Selecione o tipo de habitação...'])
//    ->label('Tipo de Habitação') ?>
<!---->
<?php //= $form->field($model, 'data[timeAlone]')
//    ->dropDownList($timeAlone, ['prompt' => 'Selecione a sua resposta...'])
//    ->label('Quantas horas o animal vai passar sozinho?') ?>
<!---->
<?php //= $form->field($model, 'data[children]')
//    ->radioList($yesNo, [
//        'item' => function($i,$label,$name,$checked,$value){
//            $id = $name.$i;
//            return '<input type="radio" class="btn-check" name="'.$name.'" id="'.$id.'" value="'.$value.'" '.($checked?'checked':'').'>
//                            <label class="btn btn-outline-primary me-2 mb-2" for="'.$id.'">'.$label.'</label>';
//        }
//    ])->label('Tem crianças em casa? Foram instruídas para os cuidados com o animal?') ?>
<!---->
<!--<hr class="my-4">-->
<!---->
<!--<h4 class="mt-4 mb-3">Custos de um animal</h4>-->
<!---->
<?php //= $form->field($model, 'data[bills]')
//    ->radioList($yesNo, [
//        'item' => function($i,$label,$name,$checked,$value){
//            $id = $name.$i;
//            return '<input type="radio" class="btn-check" name="'.$name.'" id="'.$id.'" value="'.$value.'" '.($checked?'checked':'').'>
//                            <label class="btn btn-outline-primary me-2 mb-2" for="'.$id.'">'.$label.'</label>';
//        }
//    ])->label('Está ciente dos custos? (Comida, Veterinário, etc.)')
//    ->hint('Inclui alimentação, vacinas, desparasitação, imprevistos') ?>
<!---->
<!--<hr class="my-4">-->
<!---->
<!--<h4 class="mt-4 mb-3">Acompanhamento</h4>-->
<!---->
<?php //= $form->field($model, 'data[followUp]')
//    ->radioList($yesNo, [
//        'item' => function($i,$label,$name,$checked,$value){
//            $id = $name.$i;
//            return '<input type="radio" class="btn-check" name="'.$name.'" id="'.$id.'" value="'.$value.'" '.($checked?'checked':'').'>
//                            <label class="btn btn-outline-primary me-2 mb-2" for="'.$id.'">'.$label.'</label>';
//        }
//    ])->label('Aceita visita de acompanhamento pós-adoção?') ?>
<!---->
<!--<hr class="my-4">-->
<!---->
<!--<h4 class="mt-4 mb-3">Conte-nos sobre si</h4>-->
<!---->
<?php //= $form->field($model, 'data[motive]')
//    ->textarea(['rows' => 6])
//    ->label('O que o motivou a adotar um animal?') ?>
<!---->
<!--<div class="form-group">-->
<!--    --><?php //= Html::submitButton('Submeter Candidatura', ['class' => 'btn btn-primary w-100 py-3 mt-5 text-uppercase']) ?>
<!--</div>-->
<!---->
<?php //ActiveForm::end(); ?>
