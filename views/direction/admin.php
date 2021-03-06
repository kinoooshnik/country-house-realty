<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use app\assets\AdminAsset;

/* @var $this yii\web\View */
/* @var $searchModel app\models\DirectionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

AdminAsset::register($this);
$this->title = 'Направления';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="direction-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Назад', ['/property/admin'], ['class' => 'btn btn-success']); ?>
        <?= Html::a('Добавить направление', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'pjax'=>true,
        'responsiveWrap' => false,
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'id',
            'name',
            'slug',
            [
                'class' => kartik\grid\ActionColumn::className(),
                'template' => '{update} {delete}',
            ],
        ],
    ]); ?>
</div>
