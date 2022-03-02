<?php

use app\models\Orden;
use yii\helpers\Html;
use \yii\web\YiiAsset;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Orden */

checkLogged();

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Órdenes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
YiiAsset::register($this);
?>
<div class="orden-view">

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
            'lote',
            [
                'label' => 'Variedad',
                'attribute' => 'variedad.nombre',
            ],
            [
                'label' => 'Finca',
                'attribute' => 'finca.nombre',
            ],
            [
                'label' => 'Parcela',
                'attribute' => 'parcela.nombre',
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
        ],
    ]) ?>

</div>
