<?php

use app\models\Pedidostock;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Pedidostock */

checkGestion();

$this->title = $model->proveedorMaterial->material->nombre;
$this->params['breadcrumbs'][] = ['label' => 'Pedidostocks', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="pedidostock-view">

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
                'attribute'=>'proveedorMaterial.material.nombre'
            ],
            [
                'label'=>'Proveedor',
                'attribute'=>'proveedorMaterial.proveedor.nombre'
            ],
            'cantidad',
            'fecha',
            [ 
                'attribute'=>'estado',
                'label'=>'Estado',
                'filter'=>Pedidostock::$estados,
                'format'=>'raw',
                'value'=>function($data){
                    return $data->Estado;     
                }
            ],
        ],
    ]) ?>

</div>
