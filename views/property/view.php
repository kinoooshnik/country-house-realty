<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\models\tables\Property;

/* @var $this yii\web\View */
/* @var $property app\models\views\PropertyView */
/* @var $otherPropertyViews app\models\views\PropertyView[] */

$this->title = $property->property_name;
$this->params['breadcrumbs'][] = ['label' => 'Properties', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<?php $this->registerCssFile(Url::to(['/css/view.css'])) ?>
<?php $this->registerCssFile(Url::to(['/js/slider/royalslider.css'])) ?>
<?php $this->registerCssFile(Url::to(['/js/slider/rs-default-inverted.css'])) ?>
<?php $this->registerJsFile(Url::to(['/js/jquery-3.3.1.slim.min.js']), ['position' => yii\web\View::POS_HEAD]) ?>
<?php $this->registerJsFile(Url::to(['/js/slider/jquery.royalslider.min.js']), ['position' => yii\web\View::POS_HEAD]) ?>
<?php $this->registerJsFile(Url::to(['/js/price-translation.js']), ['position' => yii\web\View::POS_HEAD]) ?>

<? $this->registerJsFile('https://api-maps.yandex.ru/2.1/?apikey=' . \Yii::$app->params['yandexMapToken'] . '&lang=ru_RU', ['position' => yii\web\View::POS_HEAD]); ?>

</div>
<div class="py-5 bg-image-full"
     style="background-image: url('<?= isset($property->photos[0]) ? $property->photos[0]['photoPath'] : '' ?>'); margin-top: -45px;"></div>
<div class="d-block mx-auto container on-blur-bg">
    <div id="gallery-t-group" class="royalSlider rsDefaultInv">
        <?php if (is_array($property->photos)): ?>
            <?php foreach ($property->photos as $photo): ?>
                <a class="rsImg" href="<?= $photo['photoPath'] ?>"><img alt="<?= $property->property_name ?>"
                                                                        class="rsTmb" src="<?= $photo['photoPath'] ?>"/></a>
            <?php endforeach; ?>
        <? endif; ?>
    </div>
</div>
<div id="slider-counter">1/6</div>
<?php $this->registerJsFile(Url::to(['/js/slider/slider-init.js']), ['position' => yii\web\View::POS_END]) ?>
<div class="bg-image-footer"></div>
<div class="container" style="padding-top: 30px">
    <div class="news-detail">
        <div class="row">
            <div class="col-md-8 mb-5">
                <?php
                if ($property->is_archive) {
                    ?>
                    <div class="alert alert-danger">
                        Этот объект находится в архиве.
                    </div>
                <?php
                }
                ?>
                <h1>
                    <?= $property->property_name ?></h1>
                <div class="row text-center">
                    <div class="col-6 col-sm card-description" data-toggle="tooltip" data-html="true"
                         title="<?= Property::$attributeLabels['property_type'] ?>">
                        <img src="<?= Url::to(['/img/duplex.svg']) ?>"
                             alt="<?= Property::$attributeLabels['property_type'] ?>">
                        <div>
                            <p><?= $property->property_type ?></p>
                        </div>
                    </div>
                    <div class="col-6 col-sm card-description" data-toggle="tooltip" data-html="true"
                         title="<?= Property::$attributeLabels['direction_id'] ?>">
                        <img src="<?= Url::to(['/img/directions.svg']) ?>"
                             alt="<?= Property::$attributeLabels['direction_id'] ?>">
                        <div>
                            <p><?= $property->direction['name'] ?></p>
                        </div>
                    </div>
                    <? if (!empty($property->build_area)): ?>
                        <div class="col-6 col-sm card-description" data-toggle="tooltip" data-html="true"
                             title="<?= Property::$attributeLabels['build_area'] ?>">
                            <img src="<?= Url::to(['/img/house.svg']) ?>"
                                 alt="<?= Property::$attributeLabels['build_area'] ?>">
                            <div>
                                <p><?= $property->build_area ?> м²</p>
                            </div>
                        </div>
                    <? endif; ?>
                    <? if (!empty($property->land_area)): ?>
                        <div class="col-6 col-sm card-description" data-toggle="tooltip" data-html="true"
                             title="<?= Property::$attributeLabels['land_area'] ?>">
                            <img src="<?= Url::to(['/img/fence.svg']) ?>"
                                 alt="<?= Property::$attributeLabels['land_area'] ?>">
                            <div>
                                <p><?= $property->land_area ?> сот.</p>
                            </div>
                        </div>
                    <? endif; ?>
                </div>
                <address>
                    <h6 class="text-muted"><?= Property::$attributeLabels['address'] ?></h6>
                    <?= $property->address ?>
                </address>
                <h6 class="text-muted"><?= Property::$attributeLabels['description'] ?></h6>
                <?= Yii::$app->formatter->asNtext($property->description) ?>
            </div>
            <div class="col-md-4 mb-5">
                <?
                $rubToUsd = 65.95;
                $rubToEur = 74.84;
                $originalCurrencyToRubAttitude = 1;
                $originalCurrencyToUsdAttitude = 1;
                $originalCurrencyToEurAttitude = 1;
                $originalCurrency = $property->currency;
                switch ($originalCurrency) {
                    case '₽':
                        $originalCurrencyToRubAttitude = 1;
                        $originalCurrencyToUsdAttitude = 1 / $rubToUsd;
                        $originalCurrencyToEurAttitude = 1 / $rubToEur;
                        break;
                    case '$':
                        $originalCurrencyToRubAttitude = $rubToUsd;
                        $originalCurrencyToUsdAttitude = 1;
                        $originalCurrencyToEurAttitude = $rubToEur / $rubToUsd;
                        break;
                    case '€':
                        $originalCurrencyToRubAttitude = $rubToEur;
                        $originalCurrencyToUsdAttitude = $rubToUsd / $rubToEur;
                        $originalCurrencyToEurAttitude = 1;
                        break;
                }
                $prices = [
                    [
                        'name' => 'sale',
                        'show' => Property::$attributeLabels['is_sale'],
                        'is_active' => $property->is_sale,
                        'price' => $property->sale_price,
                        'currency' => $property->currency,
                    ],
                    [
                        'name' => 'rent',
                        'show' => Property::$attributeLabels['is_rent'],
                        'is_active' => $property->is_rent,
                        'price' => $property->rent_price,
                        'currency' => $property->currency,
                    ],
                ];
                ?>
                <?php foreach ($prices as $price): ?>
                    <? if ($price['is_active']): ?>
                        <? $showPrice = number_format($price['price'], 0, '.', ' ') . ' ' . $price['currency']; ?>
                        <div class="d-flex flex-row">
                            <div class="d-flex flex-column flex-fill">
                                <small class="text-muted"><?= $price['show'] ?></small>
                                <h2 id="<?= $price['name'] ?>-price" data-toggle="tooltip" data-html="true"
                                    title="Цена собственника: <?= $showPrice ?>. В другие валюты конвертировано по курсу: $ <?= $rubToUsd ?>, € <?= $rubToEur ?>."><?= $showPrice ?></h2>
                            </div>
                            <div class="btn-group btn-group-toggle mb-3 ml-2 mt-3 currencies" data-toggle="buttons">
                                <label class="btn btn-outline-secondary <?= $originalCurrency == '$' ? 'active' : '' ?>">
                                    <input type="radio" name="options" id="usd-<?= $price['name'] ?>"
                                           autocomplete="off">$
                                </label>
                                <label class="btn btn-outline-secondary <?= $originalCurrency == '€' ? 'active' : '' ?>">
                                    <input type="radio" name="options" id="eur-<?= $price['name'] ?>"
                                           autocomplete="off">€
                                </label>
                                <label class="btn btn-outline-secondary <?= $originalCurrency == '₽' ? 'active' : '' ?>">
                                    <input type="radio" name="options" id="rub-<?= $price['name'] ?>"
                                           autocomplete="off">₽
                                </label>
                            </div>
                        </div>
                        <script>
                            document.getElementById('usd-<?= $price['name'] ?>').addEventListener('focus', function () {
                                translatePrice(<?=$price['price']?>, '<?=$originalCurrency?>', '$', <?=$originalCurrencyToUsdAttitude?>, '<?= $price['name'] ?>-price');
                            });
                            document.getElementById('eur-<?= $price['name'] ?>').addEventListener('focus', function () {
                                translatePrice(<?=$price['price']?>, '<?=$originalCurrency?>', '€', <?=$originalCurrencyToEurAttitude?>, '<?= $price['name'] ?>-price');
                            });
                            document.getElementById('rub-<?= $price['name'] ?>').addEventListener('focus', function () {
                                translatePrice(<?=$price['price']?>, '<?=$originalCurrency?>', '₽', <?=$originalCurrencyToRubAttitude?>, '<?= $price['name'] ?>-price');
                            });
                        </script>
                    <? endif; ?>
                <?php endforeach; ?>

                <hr>
                <div class="d-flex flex-row mt-2 mb-2">
                    <div class="flex-fill">ID:</div>
                    <div><?= $property->id?></div>
                </div>
                <?php if (!empty($property->distance_to_mrar)): ?>
                    <div class="d-flex flex-row mt-2 mb-2">
                        <div class="flex-fill">Расстояние до МКАД:</div>
                        <div><?= $property->distance_to_mrar ?> км</div>
                    </div>
                <?php endif; ?>
                <?php if (!empty($property->with_finishing)): ?>
                    <div class="d-flex flex-row mt-2 mb-2">
                        <div class="flex-fill"><?= Property::$attributeLabels['with_finishing'] ?>:</div>
                        <div><?= $property->with_finishing == 1 ? 'с отделкой' : 'без отделки' ?></div>
                    </div>
                <?php endif; ?>
                <?php if (!empty($property->with_furniture)): ?>
                    <div class="d-flex flex-row mt-2 mb-2">
                        <div class="flex-fill"><?= Property::$attributeLabels['with_furniture'] ?>:</div>
                        <div><?= $property->with_furniture == 1 ? 'с мебелью' : 'без мебели' ?></div>
                    </div>
                <?php endif; ?>
                <?php if (!empty($property->bedrooms)): ?>
                    <div class="d-flex flex-row mt-2 mb-2">
                        <div class="flex-fill"><?= Property::$attributeLabels['bedrooms'] ?>:</div>
                        <div><?= $property->bedrooms ?></div>
                    </div>
                <?php endif; ?>
                <?php if (!empty($property->bathrooms)): ?>
                    <div class="d-flex flex-row mt-2 mb-2">
                        <div class="flex-fill"><?= Property::$attributeLabels['bathrooms'] ?>:</div>
                        <div><?= $property->bathrooms ?></div>
                    </div>
                <?php endif; ?>
                <?php if (!empty($property->garage)): ?>
                    <div class="d-flex flex-row mt-2 mb-2">
                        <div class="flex-fill"><?= Property::$attributeLabels['garage'] ?>:</div>
                        <div><?= $property->garage ?></div>
                    </div>
                <?php endif; ?>
                <div class="d-flex flex-row mt-2 mb-2">
                    <?= Html::a('Позворить агенту: <nobr>' . Yii::$app->params['phones'][$property->id % 2], 'tel:' . Yii::$app->params['phones'][$property->id % 2] . '</nobr>', ['class' => 'btn btn-primary btn-block']) ?>
                </div>
                <?php if (!Yii::$app->user->isGuest): ?>
                    <?= Html::a('Изменить', ['update', 'id' => $property->id], ['class' => 'btn btn-primary btn-block']) ?>
                    <?
                    if ($property->is_archive) {
                        echo Html::a('Восстановить объект из архива', ['restore-object-to-archive', 'id' => $property->id], ['class' => 'btn btn-success btn-block']);
                    } else {
                        echo Html::a('Отправить объект в архив', ['send-object-to-archive', 'id' => $property->id], ['class' => 'btn btn-danger btn-block']);
                    }
                    ?>
                <?php endif; ?>
            </div>
        </div>

        <?php if (!empty($property->map_latitude) && !empty($property->map_longitude)): ?>
            <script type="text/javascript">
                <?php $this->registerJsFile(Url::to(['/js/yandexmap.js']), ['position' => yii\web\View::POS_END])?>
                if (typeof lat === 'undefined') {
                    var lat, lon, zoom, respondToClick, latInputId, lonInputId, addressInputId;
                }
                lat = <?=$property->map_latitude == null ? 0 : $property->map_latitude?>;
                lon = <?=$property->map_longitude == null ? 0 : $property->map_longitude?>;
                zoom = 13;
                respondToClick = false;
                latInputId = null;
                lonInputId = null;
                addressInputId = null;
            </script>
            <div id="map" class="mb-3" style="height: 400px"></div>
        <?php endif; ?>

        <h2 class="mb-4 mt-4 text-center">Другие варианты</h2>
        <?= $this->render('_cards', [
            'propertyViews' => $otherPropertyViews,
        ]) ?>
    </div>