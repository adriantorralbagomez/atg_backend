<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Pedidoinfo */

checkLogged();

$this->title = 'Create Pedidoinfo';
$this->params['breadcrumbs'][] = ['label' => 'Pedidoinfos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pedidoinfo-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
