<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\grid\GridView;
use app\models\Usuario;
use app\models\UsuarioController;
use yii\grid\ActionColumn;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

checkAdmin();

$this->title = 'Usuarios';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="usuario-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Crear Usuario', ['create'], ['class' => 'btn btn-success']) ?>
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
                'attribute'=>'usuario',
                //'filter'=>'usuario',
                'format'=>'raw',
                'value'=>function($data){
                    return Html::a($data->usuario,['usuario/view','id'=>$data->id]);
                }
            ],
            'nombre',
            'mail_corp',
            'mail_per',
            'telefono',
            [ 'attribute'=>'rol',
              'label'=>'Rol',
              'filter'=>Usuario::$roles,
              'format'=>'raw',
              'value'=>function($data){
                $ret=$data->rolText;
                if($data->rol=='CC' && $data->finca)
                    $ret.='<div style="color:magenta">Finca: '.$data->finca->nombre.'</div>';
                return $ret;        
              }
            ],
            [
                'class' => ActionColumn::class,
                
            ],
        ],
    ]); ?>


</div>
