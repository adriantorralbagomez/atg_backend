<?php

use app\models\Finca;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\grid\GridView;
use app\models\Parcela;
use yii\grid\ActionColumn;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

checkLogged();

$this->title = 'Parcelas';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="parcela-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Crear Parcela', ['create'], ['class' => 'btn btn-success']) ?>
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
                'label' => 'Variedad',
                'attribute'=>'variedad_id',
                'filter' => $this->context->getVariedades(),
                'value' => function ($data) {
                    return $data->variedad->nombre;
                }
            ],
            [
                'label' => 'Finca',
                'attribute' => 'finca_id',
                'format' => 'raw',
                'filter' => $this->context->getFincas(),
                'value' => function ($data) {
                    return $data->finca->nombre;
                }
            ],
            'nombre',
            'cant_total',
            'cant_disp',
            [
                'class' => ActionColumn::class
            ],
        ],
    ]); ?>


</div>