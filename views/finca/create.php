<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Finca */

checkLogged();

$this->title = 'Crear Finca';
$this->params['breadcrumbs'][] = ['label' => 'Fincas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="finca-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
