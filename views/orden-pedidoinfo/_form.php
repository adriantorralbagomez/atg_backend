<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\OrdenPedidoinfo */
/* @var $form yii\widgets\ActiveForm */

checkLogged();

?>

<div class="orden-pedidoinfo-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'orden_id')->textInput() ?>

    <?= $form->field($model, 'pedidoinfo_id')->textInput() ?>

    <?= $form->field($model, 'variedad_id')->textInput() ?>

    <?= $form->field($model, 'cantidad')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
