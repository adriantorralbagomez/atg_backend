<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Parcela */

checkLogged();

$this->title = 'Crear Parcela';
$this->params['breadcrumbs'][] = ['label' => 'Parcelas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="parcela-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
