<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\OrdenPedidoinfo */

checkLogged();

$this->title = 'Create Orden Pedidoinfo';
$this->params['breadcrumbs'][] = ['label' => 'Orden Pedidoinfos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="orden-pedidoinfo-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
