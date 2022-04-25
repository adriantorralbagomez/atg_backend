<?php

use app\models\Caja;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\grid\ActionColumn;
use app\models\ProveedorMaterial;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

checkAdmin();

$this->title = 'Cajas';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="caja-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Crear Caja', ['create'], ['class' => 'btn btn-success']) ?>
    </p>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'tableOptions' => ['class' => 'table table-striped'],
        'options' => [
            'class' => 'table-responsive',
        ],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'label' => 'Orden',
                'attribute'=>'orden_id',
                'filter' => $this->context->getOrdenes(),
                'value'=>'orden.lote'
            ],
            [
                'label' => 'Sector',
                'attribute'=>'sector_id',
                'filter' => $this->context->getSectores(),
                'value'=>'sector.nombre'
            ],
            
            [
                'label' => 'Tipo',
                'attribute'=>'tipocaja_id',
                'filter' => $this->context->getTiposCaja(),
                'value' => 'tipocaja.nombre',
            ],
            [
                'label' => 'Material - Proveedor',
                'attribute' => 'proveedor_material_id',
                'value' => function ($data)
                {
                    $prov_mat = ProveedorMaterial::findOne($data->proveedor_material_id);
                    if($prov_mat == null || $prov_mat == null){
                        return '(No seleccionado)';
                    }else {
                        return $prov_mat->material->nombre. ' - ' .$prov_mat->proveedor->nombre;
                    }
                },
                'filter' => ProveedorMaterial::lookup(),
            ],
            [ 
                'attribute'=>'estado',
                'label'=>'Estado',
                'filter'=>Caja::$estados,
                'format'=>'raw',
                'value'=>function($data){
                    return $data->Estado;     
                }
            ],
            [
                'class' => ActionColumn::class,
                
            ],
        ],
    ]); ?>


</div>
