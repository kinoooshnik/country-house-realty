<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $directionCards array */
/* @var $propertyViews app\models\views\PropertyView */

$this->title = 'County House - Элитная недвижимость в Москве и МО';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="property-index">
    <h2 class="mb-4" style="text-align: center">Популярные напрвления</h2>
    <?= $this->render('//direction/_cards', [
        'directionCards' => $directionCards,
    ]) ?>
    <div class="container mb-4">
        <div class="row justify-content-center">
            <a href="<?=Url::to('direction')?>" class="btn btn-primary btn-lg mx-auto" role="button">Все напрвления</a>
        </div>
    </div>

    <div class="jumbotron mb-1">
        <h1 class="display-4">Начните работать с нами!</h1>
        <p class="lead">Мы внимательно выслушаем вас и найдем то, что вы ищете.</p>
        <p class="lead">
            <a class="btn btn-primary btn-lg" href="#" role="button">Подобрать объект</a>
        </p>
    </div>

    <h2 class="mb-4" style="text-align: center">Специальные предложения</h2>
    <?= $this->render('_cards', [
        'propertyViews' => $propertyViews,
    ]) ?>

    <div class="container mb-4">
        <div class="row justify-content-center">
            <a href="<?= Url::to(['/property']) ?>" class="btn btn-primary btn-lg mx-auto" role="button">Посмотреть
                все</a>
        </div>
    </div>


    <div class="jumbotron mb-1">
        <h1 class="display-4">Хотите продать или сдать недвижимость?</h1>
        <p class="lead">У нас одна из самых больших баз постоянных клиентов, в которой точно найдется тот, кто ждет
            именно
            ваше предложение.</p>
        <p class="lead">
            <a class="btn btn-primary btn-lg" href="#" role="button">Предложить объект</a>
        </p>
    </div>

    <div class="jumbotron mb-1">
        <h1 class="display-4">О нас</h1>
        <p class="lead">Наше агентство недвижимости предоставляет услуги по покупке, продаже, сдаче в аренду недвижимости в Москве и ближнем Подмосковье, в том числе осуществляет юридическое сопровождение сделок. Мы разрабатываем дизайн интерьеров, производим благоустройство территорий, осуществляем строительство и ремонт любой сложности.</p>
    </div>
</div>