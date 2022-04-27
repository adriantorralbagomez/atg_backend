<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\grid\GridView;
use app\models\Material;
use app\models\Proveedor;
use yii\grid\ActionColumn;
use yii\bootstrap4\ActiveForm;
use app\models\ProveedorMaterial;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

checkGestion();

$this->title = 'Proveedor - materiales';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="material-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <p>
        <?= Html::a('Crear proveedor - material', ['create'], ['class' => 'btn btn-success']) ?>
        <!--Leyenda stock-->
        <span>
            <?= Html::label("No hay suficiente stock", $for = null, ['style' => 'background-color: lightcoral; padding:0.5%; border-radius:12px; color:white;']) ?>
            <?= Html::label("Queda poco stock", $for = null, ['style' => 'background-color: gold; padding:0.5%; border-radius:12px; color:white;']) ?>
            <?= Html::label("Suficiente stock", $for = null, ['style' => 'background-color: lightgreen; padding:0.5%; border-radius:12px; color:white;']) ?>
        </span>
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
                'label' => 'Material',
                'attribute' => 'material_id',
                'value' => 'material.nombre',
                'filter' => Material::lookup(),
            ],
            [
                'label' => 'Proveedor',
                'attribute' => 'proveedor_id',
                'value' => 'proveedor.nombre',
                'filter' => Proveedor::lookup(),

            ],
            [
                'label' => 'Stock Mínimo',
                'attribute' => 'material_id',
                'value' => 'material.stock_min',
                //Devuelve los stock mínimos (no duplicados/repetidos)
                'filter' => Material::filtrar_stock_min(),
            ],
            [
                'label' => 'Stock Actual',
                'attribute' => 'stock_act',
                'value' => 'stock_act',
                'contentOptions' =>  function ($data) {
                    //Se obtiene el color de fondo en base al stock mínimo y
                    //stock actual
                    $color = ProveedorMaterial::comprobarStock($data);
                    return ['style' => 'background-color:' . $color . '; color:white;'];
                },
                'filter' => ProveedorMaterial::stockActual(),
            ],
            [
                'label' => 'Precio',
                'attribute' => 'precio',
                'value' => 'precio',
            ],
            [
                'class' => ActionColumn::class,

            ],
        ],
    ]); ?>

</div>