<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $propertyForm app\models\$propertyForm */

$this->title = 'Добавить объект';
$this->params['breadcrumbs'][] = ['label' => 'Properties', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="property-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'propertyForm' => $propertyForm,
    ]) ?>

</div>
