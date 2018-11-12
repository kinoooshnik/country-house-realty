<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Property */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Properties', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="property-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'user_id',
            'property_name',
            'property_slug',
            'property_type',
            'is_sale:boolean',
            'is_rent:boolean',
            'direction_id',
            'distance_to_mrar',
            'currency',
			'sale_price',
			'rent_price',
            'address',
            'map_latitude',
            'map_longitude',
            'bathrooms',
            'bedrooms',
            'garage',
            'land_area',
            'build_area',
            'description:ntext',
            'is_archive:boolean',
            'created_at',
            'updated_at',
        ],
    ]) ?>

</div>
