<?php

use app\models\Material;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\grid\ActionColumn;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

checkLogged();

$this->title = 'Stock de materiales';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="material-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Crear Material', ['create'], ['class' => 'btn btn-success']) ?>
        <!--Leyenda stock-->
        <span>
            <?=Html::label("No hay suficiente stock", $for=null, ['style'=>'background-color: lightcoral; padding:1%; border-radius:12px; color:white;'])?>
            <?=Html::label("Queda poco stock", $for=null, ['style'=>'background-color: gold; padding:1%; border-radius:12px; color:white;'])?>
            <?=Html::label("Suficiente stock", $for=null, ['style'=>'background-color: lightgreen; padding:1%; border-radius:12px; color:white;'])?>
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
                'label' => 'Stock Mínimo',
                'attribute' => 'stock_min',
                'value' => 'stock_min',
            ],
            [
                'label' => 'Stock Actual',
                'attribute' => 'stock_act',
                'value' => 'stock_act',
                'contentOptions' =>  function ($data) {
                    //Se obtiene el color de fondo en base al stock mínimo y
                    //stock actual
                    $color = Material::comprobarStock($data);
                    return ['style' => 'background-color:'.$color.'; color:white;'];
                },
            ],
            [
                'label' => 'Tipo de caja',
                'attribute' => 'tipocaja_id',
                'filter' => $this->context->getTiposCaja(),
                'value' => 'tipocaja.nombre',
            ],

            [
                'class' => ActionColumn::class,

            ],
        ],
    ]); ?>


</div>