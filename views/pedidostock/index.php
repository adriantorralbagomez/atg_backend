<?php

use app\controllers\ProveedorMaterialController;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\grid\GridView;
use app\models\Material;
use app\models\Proveedor;
use yii\grid\ActionColumn;
use app\models\Pedidostock;
use app\models\ProveedorMaterial;
use app\models\ProveedorMaterialSearch;
use yii\bootstrap4\Dropdown;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

checkLogged();

$this->title = 'Pedidos de materiales';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="material-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <p>
        <?= Html::a('Crear Pedido de material', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'tableOptions' => ['class' => 'table table-striped'],
        'options' => [
            'class' => 'table-responsive',
        ],
        'columns' => [
            [
                'label' => 'Material - Proveedor',
                'attribute' => 'proveedor_material_id',
                'value' => function ($data)
                {
                    $prov_mat = ProveedorMaterial::findOne($data->proveedor_material_id);
                    return $prov_mat->material->nombre. ' - ' .$prov_mat->proveedor->nombre;
                },
                'filter' => ProveedorMaterial::lookup(),
            ],
            [
                'label' => 'Cantidad',
                'attribute' => 'cantidad',
                'value' => 'cantidad',
            ],
            [
                'label' => 'Fecha',
                'attribute' => 'fecha',
                'value' => 'fecha',
            ],
            [ 
                'label'=>'Estado',
                'attribute'=>'estado',
                'filter'=>Pedidostock::$estados,
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