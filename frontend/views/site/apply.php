<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this
 * @var \common\models\Animal $animal
 * @var common\models\Application $model*/



$this->title = 'Candidatura';
?>

<div class="container py-5">
    <h1 class="text-uppercase border-start border-5 border-primary ps-3 mb-4">
        <?= Html::encode($this->title) ?>
    </h1>

    <p>Está prestes a candidatar-se para a adoção do(a) <?= Html::encode($animal->name) ?>.</p>

</div>

<div class="bg-light rounded p-4 p-sm-5">

    <p> Aviso de Processo: "Obrigado pelo seu interesse!
        O preenchimento deste formulário é o primeiro passo para a adoção.</p>

    <p> Honestidade: "Por favor, seja o mais honesto e detalhado possível.
        As suas respostas ajudam-nos a perceber se este é o animal certo para si e para a sua família."</p>

    <p> Próximos Passos: "A sua candidatura será revista pelo atual cuidador do animal (seja uma associação ou um particular),
        que entrará em contacto consigo se o seu perfil for considerado compatível."</p>



<?php $form = ActiveForm::begin([
    'id' => 'apply-form',
    'action' => ['/site/apply', 'animal_id' => $animal->id],
    'method' => 'post',
]); ?>



<?php $form = ActiveForm::end()?>

</div>
