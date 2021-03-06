<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\grid\GridView;
use app\models\Material;
use yii\grid\ActionColumn;
use yii\bootstrap4\ActiveForm;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

checkGestion();

$this->title = 'Materiales';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="material-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <p>
        <?= Html::a('Crear Material', ['create'], ['class' => 'btn btn-success']) ?>
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
                'label' => 'Nombre',
                'attribute' => 'nombre',
                'value' => 'nombre',
            ],
            [
                'label' => 'Descripción',
                'attribute' => 'descripcion',
                'value' => 'descripcion',
            ],
            [
                'label' => 'Tipo de caja',
                'attribute' => 'tipocaja_id',
                'filter' => $this->context->getTiposCaja(),
                'value' => 'tipocaja.nombre',
            ],
            [
                'label' => 'Stock Mínimo',
                'attribute' => 'stock_min',
                'value' => 'stock_min',
            ],
            [
                'label' => 'Stock Actual Total',
                'attribute' => 'stock_act',
                'value' => function ($data) {
                    return Material::calc_stock_act($data->id);
                },
                'contentOptions' =>  function ($data) {
                    //Se obtiene el color de fondo en base al stock mínimo y
                    //stock actual
                    $color = Material::comprobarStock($data, Material::calc_stock_act($data->id));
                    return ['style' => 'background-color:' . $color . '; color:white;'];
                },
                'filter' => Material::stockActual(),
            ],
            [
                'class' => ActionColumn::class,

            ],
        ],
    ]); ?>

</div>