<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\ProveedorMaterial */

checkGestion();

$this->title = 'Actualizar Proveedor Material: ' . $model->material->nombre;
$this->params['breadcrumbs'][] = ['label' => 'Proveedor Materials', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="proveedor-material-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
