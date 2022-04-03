<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Tipocaja */
/* @var $form yii\widgets\ActiveForm */

checkAdmin();

?>

<div class="tipocaja-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'nombre')->textInput() ?>
    
    <?= $form->field($model, 'pesokg')->textInput() ?>

    <?= $form->field($model, 'detalles')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton('Guardar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
