<?php

use yii\helpers\Url;
use app\models\Finca;
use app\models\Orden;
use yii\helpers\Html;
use yii\grid\GridView;
use app\models\Variedad;
use yii\grid\ActionColumn;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

checkAdmin();

$this->title = 'Órdenes';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="orden-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Crear Órden', ['create'], ['class' => 'btn btn-success']) ?>
    </p>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'tableOptions' => ['class' => 'table table-striped'],
        'options' => [
            'class' => 'table-responsive',
        ],
        'columns' => [
            'lote',
            [
                'label' => 'Variedad',
                'attribute'=>'variedad_id',
                'filter' => Variedad::lookup(),
                'value' => 'variedad.nombre',
            ],
            [
                'label' => 'Finca',
                'attribute'=>'finca_id',
                'filter' => Finca::lookup(),
                'value' => 'finca.nombre',
            ],
            [
                'label' => 'Parcela',
                'attribute'=>'parcela_id',
                'value' => 'parcela.nombre',
            ],
            'fecha',
            'cantidad',
            [ 
                'attribute'=>'estado',
                'label'=>'Estado',
                'filter'=>Orden::$estados,
                'format'=>'raw',
                'value'=>function($data){
                    return $data->Estado;     
                }
            ],
            [
                'format'=>'raw',
                'value'=>function($data){
                    return Html::a('<i class="bi bi-printer-fill"></i>',['etiqueta/print','lote'=>$data->lote]);
                }
            ],
            [
                'class' => ActionColumn::class,
            ]
        ],
    ]); ?>


</div>
