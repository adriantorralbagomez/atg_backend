<?php

use yii\helpers\Url;
use app\models\Finca;
use yii\helpers\Html;
use yii\grid\GridView;
use app\models\Parcela;
use app\models\Variedad;
use app\models\Pedidoinfo;
use yii\grid\ActionColumn;
use yii\widgets\ActiveForm;
use yii\grid\CheckboxColumn;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

checkLogged();

$this->title = 'Información del pedido';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pedidoinfo-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>  
    <?= Html::a('Crear información de pedido', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'tableOptions' => ['class' => 'table table-striped'],
        'options' => [
            'class' => 'table-responsive',
        ],
        'columns' => [
            'pedido_id',
            [
                'label' => 'Variedad',
                'attribute' => 'variedad_id',
                'filter' => $this->context->getVariedades(),
                'value' => 'variedad.nombre'
            ],
            'cantidad',
            [
                'class' => ActionColumn::class,
                'urlCreator' => function ($action, Pedidoinfo $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]); ?>

</div>
