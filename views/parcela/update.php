<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Parcela */

checkLogged();

$this->title = 'Actualizar Parcela: ' . $model->nombre;
$this->params['breadcrumbs'][] = ['label' => 'Parcelas', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->nombre, 'url' => ['view', 'id' => $model->nombre]];
$this->params['breadcrumbs'][] = 'Actualizar';
?>
<div class="parcela-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
