<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use app\assets\AdminAsset;

AdminAsset::register($this);
/* @var $this yii\web\View */
/* @var $searchModel app\models\PropertySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Недвижимость';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="property-admin">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Добавить объект', ['create'], ['class' => 'btn btn-success']) ?>
        <?= Html::a('Направления', '\direction', ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'responsiveWrap' => false,
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            [
                'attribute' => 'image',
                'format' => 'html',
                'label' => 'Фото',
                'value' => function ($model) {
                    return Html::a(Html::img($model->getMainPhotoURL(), ['height'=>'75']), ['update', 'id' => $model->id]);
                },
            ],

            'id',
            'property_name',
            'property_type',
            'is_sale:boolean',
            'is_rent:boolean',
            //'direction_id',
            //'distance_to_mrar',
            'currency',
            'sale_price',
            'rent_price',
            'address',
            //'map_latitude',
            //'map_longitude',
            //'bathrooms',
            //'bedrooms',
            //'garage',
            //'land_area',
            //'build_area',
            //'description:ntext',
            //'property_features',
            'is_archive:boolean',
            //'created_at',
            //'updated_at',
            [
                'class' => kartik\grid\ActionColumn::className(),
            ],
        ],
    ]); ?>
</div>
