<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Variedad */

checkAdmin();

$this->title = 'Actualizar Variedad: ' . $model->nombre;
$this->params['breadcrumbs'][] = ['label' => 'Variedades', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->nombre, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Actualizar';
?> 
<div class="variedad-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
