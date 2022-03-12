<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\grid\GridView;
use app\models\ProveedorMaterial;
use yii\grid\ActionColumn;
use yii\bootstrap4\ActiveForm;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

checkLogged();

$this->title = 'Proveedor - materiales';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="material-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php $form = ActiveForm::begin(); ?>
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
                'label' => 'material_id',
                'attribute' => 'material_id',
                'value' => 'material_id',
            ],
            [
                'label' => 'proveedor_id',
                'attribute' => 'proveedor_id',
                'value' => 'proveedor_id',
            ],
            [
                'class' => ActionColumn::class,

            ],
        ],
    ]); ?>

    <?php ActiveForm::end(); ?>
</div>