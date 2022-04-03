<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Parcela;

/* @var $this yii\web\View */
/* @var $model app\models\Parcela */
/* @var $form yii\widgets\ActiveForm */

checkAdmin();

?>

<div class="parcela-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'variedad_id')->dropDownList($this->context->getVariedades(), ['prompt' => 'Selecciona variedad...']) ?>

    <?= $form->field($model, 'finca_id')->dropDownList($this->context->getFincas(), ['prompt' => 'Selecciona finca...']) ?>

    <?= $form->field($model, 'nombre')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'cant_total')->textInput() ?>

    <?= $form->field($model, 'cant_disp')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Guardar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
