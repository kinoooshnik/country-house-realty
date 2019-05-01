<?php

/* @var $this yii\web\View */

use app\widgets\Article;
use yii\helpers\Url;

$this->title = 'Контакты';
$this->params['breadcrumbs'][] = $this->title;

Article::begin(['image' => ['/img/contact-bg.jpg'], 'title' => $this->title]);
?>
    <div class="row">
        <div class="col center-block contacts">
            <ul class="list-unstyled text-decoration-none">
                <li class="h5 m-0" style="border-left: none;"><?= Yii::$app->name ?></li>
                <?php foreach (Yii::$app->params['phones'] as $phone): ?>
                    <li>
                        <i>
                            <object type="image/svg+xml" data="<?= Url::to(['/img/phone.svg']) ?>" width="22"
                                    height="22"></object>
                        </i>
                        <a href="tel:<?= $phone ?>" class="h5 tel"><?= $phone ?></a>
                    </li>
                <?php endforeach; ?>
                <li>
                    <i>
                        <object type="image/svg+xml" data="<?= Url::to(['/img/map-pin.svg']) ?>" width="22"
                                height="22"></object>
                    </i>
                    <div class="h5"><?= Yii::$app->params['address']['full'] ?></div>
                </li>
            </ul>
        </div>
        <div class="col"></div>
    </div>
    <script type="text/javascript">
        <? $this->registerJsFile('https://api-maps.yandex.ru/2.1/?apikey=' . \Yii::$app->params['yandexMapToken'] . '&lang=ru_RU', ['position' => yii\web\View::POS_HEAD]); ?>
        <?php $this->registerJsFile(Url::to(['/js/yandexmap.js']), ['position' => yii\web\View::POS_END])?>
        if (typeof lat === 'undefined') {
            var lat, lon, zoom, respondToClick, latInputId, lonInputId, addressInputId;
        }
        lat = <?=Yii::$app->params['address']['lat']?>;
        lon = <?=Yii::$app->params['address']['lon']?>;
        zoom = 15;
        respondToClick = false;
        latInputId = null;
        lonInputId = null;
        addressInputId = null;
    </script>
    <div id="map" class="mb-3" style="height: 400px"></div>

<?php
Article::end();