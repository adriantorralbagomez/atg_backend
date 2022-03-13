<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\grid\GridView;
use app\models\Material;
use app\models\Proveedor;
use yii\grid\ActionColumn;
use yii\bootstrap4\ActiveForm;
use app\models\ProveedorMaterial;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

checkLogged();

$this->title = 'Proveedor - materiales';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="material-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <p>
        <?= Html::a('Crear proveedor - material', ['create'], ['class' => 'btn btn-success']) ?>
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
                'label' => 'Material',
                'attribute' => 'material_id',
                'value' => 'material.nombre',
                'filter' => Material::lookup(),
            ],
            [
                'label' => 'Proveedor',
                'attribute' => 'proveedor_id',
                'value' => 'proveedor.nombre',
                'filter' => Proveedor::lookup(),

            ],
            [
                'label' => 'Precio',
                'attribute' => 'precio',
                'value' => 'precio',
            ],
            [
                'class' => ActionColumn::class,

            ],
        ],
    ]); ?>

</div>