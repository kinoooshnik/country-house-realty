<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\PropertySearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="property-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'property_name') ?>

    <?= $form->field($model, 'property_slug') ?>

    <?= $form->field($model, 'property_type') ?>

    <?= $form->field($model, 'is_sale') ?>

    <?php // echo $form->field($model, 'is_rent') ?>

    <?php // echo $form->field($model, 'currency') ?>

    <?php // echo $form->field($model, 'price') ?>

    <?php // echo $form->field($model, 'address') ?>

    <?php // echo $form->field($model, 'map_latitude') ?>

    <?php // echo $form->field($model, 'map_longitude') ?>

    <?php // echo $form->field($model, 'direction_id') ?>

    <?php // echo $form->field($model, 'distance_to_mrar') ?>

    <?php // echo $form->field($model, 'with_finishing') ?>

    <?php // echo $form->field($model, 'with_furniture') ?>

    <?php // echo $form->field($model, 'bathrooms') ?>

    <?php // echo $form->field($model, 'bedrooms') ?>

    <?php // echo $form->field($model, 'garage') ?>

    <?php // echo $form->field($model, 'land_area') ?>

    <?php // echo $form->field($model, 'build_area') ?>

    <?php // echo $form->field($model, 'description') ?>

    <?php // echo $form->field($model, 'user_id') ?>

    <?php // echo $form->field($model, 'is_archive') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
