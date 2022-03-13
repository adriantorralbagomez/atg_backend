<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\grid\GridView;
use app\models\Material;
use yii\grid\ActionColumn;
use yii\bootstrap4\ActiveForm;

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
        <!--Leyenda stock-->
        <span>
            <?= Html::label("No hay suficiente stock", $for = null, ['style' => 'background-color: lightcoral; padding:1%; border-radius:12px; color:white;']) ?>
            <?= Html::label("Queda poco stock", $for = null, ['style' => 'background-color: gold; padding:1%; border-radius:12px; color:white;']) ?>
            <?= Html::label("Suficiente stock", $for = null, ['style' => 'background-color: lightgreen; padding:1%; border-radius:12px; color:white;']) ?>
        </span>
    </p>

    <?php 
        // echo $form->field($searchModel, 'stock_act')->dropDownList(Material::stockActual(),
        // ['prompt' => 'Selecciona stock actual...','id'=>'selvar']); 

        // $url = yii\helpers\Url::to(['material/index']);
        // $this->registerJs("$('#selvar').on('change', function() {
        // window.location.href='$url&stock_act='+$(this).val();
        // });",
        // \yii\web\View::POS_READY,
        // 'my-button-handler'
        // );

    ?>

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
                'label' => 'Stock Mínimo',
                'attribute' => 'stock_min',
                'value' => 'stock_min',
            ],
            [
                'label' => 'Stock Actual',
                'attribute' => 'stock_act',
                'value' => 'stock_act',
                'contentOptions' =>  function ($data) {
                    //Se obtiene el color de fondo en base al stock mínimo y
                    //stock actual
                    $color = Material::comprobarStock($data);
                    return ['style' => 'background-color:' . $color . '; color:white;'];
                },
                
            ],
            [
                'label' => 'Tipo de caja',
                'attribute' => 'tipocaja_id',
                'filter' => $this->context->getTiposCaja(),
                'value' => 'tipocaja.nombre',
            ],

            [
                'class' => ActionColumn::class,

            ],
        ],
    ]); ?>

</div>