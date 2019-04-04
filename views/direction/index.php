<?php

/* @var $this yii\web\View */
/* @var $directionCards array */

$this->title = 'Направления | County House - Элитная недвижимость в Москве и МО';
$this->params['breadcrumbs'][] = $this->title;

use yii\helpers\Url; ?>
<div class="property-index">
    <h2 class="mb-4" style="text-align: center">Все напрвления</h2>
    <?= $this->render('_cards', [
        'directionCards' => $directionCards,
    ]) ?>

    <div class="container mb-4">
        <div class="row justify-content-center">
            <a href="<?= Url::to('\\') ?>" class="btn btn-primary btn-lg mx-auto" role="button">На главную</a>
        </div>
    </div>
</div>