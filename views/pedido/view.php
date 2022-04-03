<?php

use yii\helpers\Html;
use app\models\Pedido;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Pedido */

checkAdmin();

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Pedidos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="pedido-view">

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
            [
                'label' => 'Cliente',
                'attribute' => 'cliente.nombre',
            ],
            'cantidad',
            [ 'attribute'=>'estado',
              'label'=>'Estados',
              'filter'=>Pedido::$estados,
              'value'=>function($data){
                return $data->estadoText;
              }
            ],
            'fecha',
        ],
    ]) ?>

</div>
