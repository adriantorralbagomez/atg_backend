<?php

use app\models\Orden;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Orden */
/* @var $form yii\widgets\ActiveForm */

checkLogged();
/*
<?= $form->field($model, 'variedad_id')->dropDownList($this->context->getVariedades(), ['prompt' => 'Selecciona variedad...']) ?>

    <?= $form->field($model, 'finca_id')->dropDownList($this->context->getFincas(), ['prompt' => 'Selecciona finca...']) ?>
*/

?>

<div class="orden-form">

    <?php $form = ActiveForm::begin(); ?>
    
    <?= $form->field($model, 'lote')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'variedad_id')->dropDownList($this->context->getVariedades(), ['prompt' => 'Selecciona variedad...']) ?>

    <?= $form->field($model, 'finca_id')->dropDownList($this->context->getFincas(), ['prompt' => 'Selecciona finca...']) ?>
    
    <?= $form->field($model, 'parcela_id')->dropDownList($this->context->getParcelas(), ['prompt' => 'Selecciona parcela...']) ?>

    <?= $form->field($model, 'fecha')->textInput() ?>

    <?= $form->field($model, 'cantidad')->textInput() ?>

    <?= $form->field($model, 'estado')->dropDownList(Orden::$estados, ['prompt' => 'Selecciona estado...']) ?>

    <div class="form-group">
        <?= Html::submitButton('Guardar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
