<?php

use app\models\Pedidostock;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Pedidostock */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="pedidostock-form">

    <?php $form = ActiveForm::begin(); ?>
    <!--Arreglar esto!!!!-->
    <?= $form->field($model, 'proveedor_material_id')->dropDownList($this->context->getMateriales(), ['prompt' => 'Selecciona Material...']) ?>
    <!--Arreglar esto!!!!-->
    <?= $form->field($model, 'proveedor_material_id')->dropDownList($this->context->getProveedores(), ['prompt' => 'Selecciona Proveedor...']) ?>

    <?= $form->field($model, 'cantidad')->textInput() ?>

    <?= $form->field($model, 'fecha')->textInput() ?>

    <?= $form->field($model, 'estado')->dropDownList(Pedidostock::$estados, ['prompt' => 'Selecciona estado...']) ?>

    <div class="form-group">
        <?= Html::submitButton('Guardar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
