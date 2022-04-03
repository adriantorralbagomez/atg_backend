<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Variedad */

checkAdmin();

$this->title = 'Crear Variedad';
$this->params['breadcrumbs'][] = ['label' => 'Variedades', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="variedad-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
