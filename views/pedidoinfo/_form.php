<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Pedidoinfo */
/* @var $form yii\widgets\ActiveForm */

checkLogged();

?>

<div class="pedidoinfo-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'pedido_id')->textInput() ?>

    <?= $form->field($model, 'variedad_id')->textInput() ?>

    <?= $form->field($model, 'cantidad')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Guardar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
