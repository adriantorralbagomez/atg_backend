<?php

use yii\helpers\Url;
use app\models\Finca;
use app\models\Nlote;
use yii\helpers\Html;
use yii\grid\GridView;
use app\models\Parcela;
use app\models\Variedad;
use app\models\Pedidoinfo;
use yii\grid\ActionColumn;
use yii\widgets\ActiveForm;
use yii\grid\CheckboxColumn;
use kartik\datecontrol\Module;
use kartik\datecontrol\DateControl;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

checkLogged();

$this->title = 'Crear orden';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pedidoinfo-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin(); ?>

    <p>
    <div class="form-group">
        <?= Html::submitButton('Guardar', ['class' => 'btn btn-success']) ?>
    </div>    
    <!-- <?= Html::a('Crear información de pedido', ['create'], ['class' => 'btn btn-success']) ?> -->
    </p>

    <?php 
        //Campo form que cambia a la página de eso
        echo $form->field($model, 'variedad_id')->dropDownList(Variedad::lookup(),
        ['prompt' => 'Selecciona variedad...','id'=>'selvar']); 

        $url = yii\helpers\Url::to(['orden/create']);
        $this->registerJs("$('#selvar').on('change', function() {
        window.location.href='$url&variedad='+$(this).val();
        });",
        \yii\web\View::POS_READY,
        'my-button-handler'
        );

    ?>

    <!--EL CAMPO PARCELA SOLO ESTARÁ DESHABILITADO CUANDO NO SE HAYA ELEGIDO UNA VARIEDAD-->
    <!--No harán falta más comprobaciones, ya que con que falte este dato, no se podrá insertar la orden en general-->
    <?= $form->field($model, 'parcela_id')->dropDownList(Parcela::lookup($model->variedad_id), ['prompt' => 'Selecciona parcela...','disabled' => !(strlen($model->variedad_id) > 0)]) ?>
    <?= $form->field($model, 'fecha')->widget(DateControl::class) ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'tableOptions' => ['class' => 'table table-striped'],
        'options' => [
            'class' => 'table-responsive',
        ],
        'columns' => [
            ['class' => CheckboxColumn::class,'name'=>'ids',
			'checkboxOptions' => function ($model, $key, $index, $column) {
						return ['value' => $model->id];
					     },],
            'pedido_id',
            [
                'label' => 'Variedad',
                'attribute' => 'variedad_id',
                'filter' => Variedad::lookup(),
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

    <?php ActiveForm::end(); ?>

</div>
