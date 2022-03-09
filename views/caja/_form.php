<?php

use app\models\Caja;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Caja */
/* @var $form yii\widgets\ActiveForm */

checkLogged();

?>

<div class="caja-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'orden_id')->dropDownList($this->context->getOrdenes(), ['prompt' => 'Selecciona finca...']) ?>

    <?= $form->field($model, 'sector_id')->dropDownList($this->context->getSectores(), ['prompt' => 'Selecciona finca...']) ?>

    <?= $form->field($model, 'tipocaja_id')->dropDownList($this->context->getTiposCaja(),['prompt' => 'Selecciona tipo de caja...']) ?>

    <?= $form->field($model, 'etiqueta_id')->textInput() ?>

    <?= $form->field($model, 'estado')->dropDownList(Caja::$estados, ['prompt' => 'Selecciona estado...']) ?>

    <div class="form-group">
        <?= Html::submitButton('Guardar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
