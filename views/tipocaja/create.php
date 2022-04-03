<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Tipocaja */

checkAdmin();

$this->title = 'Crear Tipocaja';
$this->params['breadcrumbs'][] = ['label' => 'Tipo de caja', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tipocaja-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
