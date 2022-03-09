<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Finca */

checkLogged();

$this->title = 'Actualizar Finca: ' . $model->nombre;
$this->params['breadcrumbs'][] = ['label' => 'Fincas', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->nombre, 'url' => ['view', 'id' => $model->nombre]];
$this->params['breadcrumbs'][] = 'Actualizar';
?>
<div class="finca-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
