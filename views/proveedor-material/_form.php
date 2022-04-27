<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ProveedorMaterial */
/* @var $form yii\widgets\ActiveForm */

checkGestion();
?>

<div class="proveedor-material-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'material_id')->dropDownList($this->context->getMateriales(), ['prompt' => 'Selecciona Material...']) ?>

    <?= $form->field($model, 'proveedor_id')->dropDownList($this->context->getProveedores(), ['prompt' => 'Selecciona Proveedor...']) ?>

    <?= $form->field($model, 'stock_act')->textInput() ?>

    <?= $form->field($model, 'precio')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Guardar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
