<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\grid\GridView;
use app\models\Proveedor;
use yii\grid\ActionColumn;
use yii\bootstrap4\ActiveForm;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

checkGestion();

$this->title = 'Proveedores';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="material-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <p>
        <?= Html::a('Crear Proveedor', ['create'], ['class' => 'btn btn-success']) ?>
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
                'class' => ActionColumn::class,

            ],
        ],
    ]); ?>

</div>