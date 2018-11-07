<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $propertyForm app\models\PropertyForm */

$this->title = 'Изменение объекта: ' . $propertyForm->property_name;
?>
<div class="property-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'propertyForm' => $propertyForm,
    ]) ?>

</div>
