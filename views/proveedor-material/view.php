<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\ProveedorMaterial */

checkGestion();

$this->title = $model->material->nombre;
$this->params['breadcrumbs'][] = ['label' => 'Proveedor Materials', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="proveedor-material-view">

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
            [
                'label'=>'Material',
                'attribute'=>'material.nombre'
            ],
            [
                'label'=>'Proveedor',
                'attribute'=>'proveedor.nombre'
            ],
            'stock_min',
            'stock_act',
            'precio',
        ],
    ]) ?>

</div>
