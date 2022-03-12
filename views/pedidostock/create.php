<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Pedidostock */

$this->title = 'Crear Pedidostock';
$this->params['breadcrumbs'][] = ['label' => 'Pedidostocks', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pedidostock-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
