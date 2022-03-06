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
                'label' => 'Nombre',
                'attribute'=>'nombre',
                'value' => 'nombre',
            ],
            [
                'label' => 'Descripción',
                'attribute'=>'descripcion',
                'value' => 'descripcion',
            ],
            [
                'label' => 'Stock Mínimo',
                'attribute'=>'stock_min',
                'value' => 'stock_min',
            ],
            [
                'label' => 'Stock Actual',
                'attribute'=>'stock_act',
                'value' => 'stock_act',
                'contentOptions' =>  function ($data) {
                    if($data->stock_act > $data->stock_min){ 
                        //Si el stock actual supera el mínimo
                        //Calcular porcentaje del stock mínimo sobre el stock actual
                        $dif = $data->stock_act - $data->stock_min;
                        $porcentaje = ((float)$dif * 100) / $data->stock_act;
                        $porcentaje = round($porcentaje, 0);  //Eliminar los decimales
                        
                        if ($porcentaje <= 30) {
                            //Cerca del mínimo de stock
                            return ['style' => 'background-color:LightCoral; color:white;'];
                        } else if($porcentaje <=60) {
                            //Stock "normal"
                            return ['style' => 'background-color:Gold; color:white;'];
                        }else if($porcentaje > 60) {
                            //Hay stock de sobra
                            return ['style' => 'background-color:LightGreen; color:white;'];
                        }
                    }else{
                        //Stock por debajo de mínimos
                        return ['style' => 'background-color:LightCoral; color:white;'];
                    }
                },
            ],
            [
                'label' => 'Tipo',
                'attribute'=>'tipocaja_id',
                'filter' => $this->context->getTiposCaja(),
                'value' => 'tipocaja.nombre',
            ],
            
            [
                'class' => ActionColumn::class,
                
            ],
        ],
    ]); ?>


</div>
