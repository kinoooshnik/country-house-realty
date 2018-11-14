<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use app\assets\AdminAsset;

AdminAsset::register($this);

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Пользователи';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Добавить пользователя', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <div class="embed-responsive">
        <?php Pjax::begin(['enablePushState' => false]); ?>
        <?= GridView::widget([
            'responsiveWrap' => false,
            'dataProvider' => $dataProvider,
            'columns' => [
                'id',
                'email:email',
                'first_name',
                'second_name',
                [
                    'class' => kartik\grid\ActionColumn::className(),
                    'template' => '{update} {delete}',
                ],
            ],
        ]); ?>
        <?php Pjax::end(); ?>
    </div>
</div>
