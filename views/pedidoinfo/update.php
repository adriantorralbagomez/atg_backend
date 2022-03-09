<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Pedidoinfo */

checkLogged();

$this->title = 'Update Pedidoinfo: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Pedidoinfos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->pedido_id, 'url' => ['view', 'id' => $model->pedido_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="pedidoinfo-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
