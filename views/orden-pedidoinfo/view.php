<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\OrdenPedidoinfo */

checkLogged();

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Orden Pedidoinfos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="orden-pedidoinfo-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'orden_id',
            'pedidoinfo_id',
            'variedad_id',
            [
                'label' => 'Variedad',
                'attribute' => 'variedad.nombre'
            ],
            'cantidad',
        ],
    ]) ?>

</div>
