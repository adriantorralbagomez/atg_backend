<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Caja */

checkAdmin();

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Cajas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="caja-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Actualizar', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Eliminar', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => '¿Está segur@ de que desea eliminar este elemento?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            [
                'label' => 'Orden',
                'attribute' => 'orden.nombre',
            ],
            [
                'label' => 'Sector',
                'attribute' => 'sector.nombre',
            ],
            [
                'label' => 'Tipo',
                'attribute' => 'tipocaja.nombre',
            ],
            [
                'label'=>'Material',
                'attribute'=>'proveedorMaterial.material.nombre'
            ],
            [
                'label'=>'Proveedor',
                'attribute'=>'proveedorMaterial.proveedor.nombre'
            ],
            [
                'label' => 'Etiqueta',
                'attribute' => 'etiqueta.nombre',
            ],
            'estado',
        ],
    ]) ?>

</div>
