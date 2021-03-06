<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\OrdenPedidoinfo */

checkAdmin();

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Orden Pedidoinfos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="orden-pedidoinfo-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Actualizar', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Eliminar', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Desea eliminar este elemento?',
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
