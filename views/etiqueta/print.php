<?php

use yii\helpers\Html;
use app\models\Tipocaja;
use yii\widgets\ActiveForm;

checkLogged();

$this->title = 'Imprimir Etiquetas';
$this->params['breadcrumbs'][] = ['label' => 'Etiquetas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
/* @var $this yii\web\View */
/* @var $model app\models\Etiqueta */
/* @var $form yii\widgets\ActiveForm */
if(isset($_GET['tipocaja'])){
    echo "a";
}
?>

<div class="etiqueta-form">

    <form method="GET" action="index.php?r=etiqueta">

    <div class="card p-5 m-1" style="background-color: #e9ecef;">
    
    <h1>Lote: <?= $_GET['lote']?></h1>
    <br>
    <h4 style="font-weight:normal;">Tipo caja</h4>
    <select name="tipocaja" class="form-control">
        <?php
            foreach (Tipocaja::lookup() as $key => $value) {
                if($value!="Caja final"){
                    echo "<option value=$key>$value</option>";
                }
            }
        ?>
    </select>
    <br>
    <h4 style="font-weight:normal;">Nº de etiquetas</h4>
    <input type="number" name="cant" placeholder="Nº de etiquetas" min="0" max="100000" class="form-control">
    <br>
    </div>
    <br>
    <div class="form-group">
        <button class="btn btn-success">Imprimir etiquetas</button>
    </div>

    </form>

</div>
