<?php

use app\models\Caja;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\grid\ActionColumn;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

checkLogged();

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
            // [
            //     'label' => 'Etiqueta',
            //     'attribute' => 'etiqueta_id',
            //     'value'=>'etiqueta.nombre'
            // ],
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
