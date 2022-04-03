<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Sector */

checkAdmin();

$this->title = 'Actualizar Sector: ' . $model->nombre;
$this->params['breadcrumbs'][] = ['label' => 'Sectores', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->nombre, 'url' => ['view', 'id' => $model->nombre]];
$this->params['breadcrumbs'][] = 'Actualizar';
?>
<div class="sector-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
