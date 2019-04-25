<?php

/* @var $this \yii\web\View */

/* @var $content string */

use app\assets\AppAsset;
use app\widgets\Alert;
use yii\helpers\Html;
use yii\bootstrap4\Nav;
use yii\bootstrap4\NavBar;
use yii\helpers\Url;

AppAsset::register($this);
$this->registerJsFile('/js/popper.min.js', []);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>

</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => Yii::$app->name,
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-expand-lg navbar-dark bg-dark fixed-top',
        ],
    ]);
    $outOfCityUrl = '/property?
PropertyListSearch%5Bproperty_type%5D=&
PropertyListSearch%5Bad_type%5D%5B%5D=%D0%90%D1%80%D0%B5%D0%BD%D0%B4%D0%B0&
PropertyListSearch%5Bprice_from%5D=&
PropertyListSearch%5Bprice_to%5D=&
PropertyListSearch%5Bcurrency%5D=&
PropertyListSearch%5Bdirection_id%5D=&
PropertyListSearch%5Bdirection_id%5D%5B%5D=5&
PropertyListSearch%5Bdirection_id%5D%5B%5D=6&
PropertyListSearch%5Bdirection_id%5D%5B%5D=7&
PropertyListSearch%5Bdirection_id%5D%5B%5D=8&
PropertyListSearch%5Bdirection_id%5D%5B%5D=9&
PropertyListSearch%5Bdirection_id%5D%5B%5D=10&
PropertyListSearch%5Bdirection_id%5D%5B%5D=4&
PropertyListSearch%5Bdirection_id%5D%5B%5D=11&
PropertyListSearch%5Bdirection_id%5D%5B%5D=3&
PropertyListSearch%5Bdirection_id%5D%5B%5D=16&
PropertyListSearch%5Bdirection_id%5D%5B%5D=2&
PropertyListSearch%5Bdirection_id%5D%5B%5D=12&
PropertyListSearch%5Bdirection_id%5D%5B%5D=13&
PropertyListSearch%5Bdirection_id%5D%5B%5D=1&
PropertyListSearch%5Bdirection_id%5D%5B%5D=14&
PropertyListSearch%5Bdirection_id%5D%5B%5D=15&
PropertyListSearch%5Bbuild_area_from%5D=&
PropertyListSearch%5Bbuild_area_to%5D=&
PropertyListSearch%5Bland_area_from%5D=&
PropertyListSearch%5Bland_area_to%5D=&
PropertyListSearch%5Bwith_finishing%5D=&
PropertyListSearch%5Bwith_furniture%5D=&
PropertyListSearch%5Bdistance_to_mrar_from%5D=&
PropertyListSearch%5Bdistance_to_mrar_to%5D=&
PropertyListSearch%5Bbedrooms%5D=&
PropertyListSearch%5Bgarage%5D=&
PropertyListSearch%5Bbathrooms%5D=';
    $items = [
        ['label' => 'Все объекты', 'url' => ['/property']],
        [
            'label' => 'Город',
            'items' => [
                ['label' => 'Аренда', 'url' => ['/property', 'PropertyListSearch[ad_type][]' => 'Аренда', 'PropertyListSearch[direction_id][]' => 17]],
                ['label' => 'Продажа', 'url' => ['/property', 'PropertyListSearch[ad_type][]' => 'Продажа', 'PropertyListSearch[direction_id][]' => 17]],
            ],
        ],
        [
            'label' => 'Загород',
            'items' => [
                ['label' => 'Аренда', 'url' => $outOfCityUrl],
                ['label' => 'Продажа', 'url' => str_replace('%D0%90%D1%80%D0%B5%D0%BD%D0%B4%D0%B0', '%D0%9F%D1%80%D0%BE%D0%B4%D0%B0%D0%B6%D0%B0', $outOfCityUrl)],
                '<div class="dropdown-divider"></div>',
                ['label' => 'Направления', 'url' => ['/direction']],
            ],
        ],
        [
            'label' => 'Клиентам',
            'items' => [
                ['label' => 'Владельцам', 'url' => ['/to-owners']],
                ['label' => 'Подобрать объект', 'url' => ['/contacts/find']],
                ['label' => 'Задать вопрос', 'url' => ['/contacts/ask']],
                ['label' => 'Строительство и дизайн', 'url' => ['/design']],
                ['label' => 'Советы эксперта', 'url' => ['/advices']],
                ['label' => 'Статьи', 'url' => ['/articles']],
                ['label' => 'О Нас', 'url' => ['/about']],
            ],
        ],
        ['label' => 'Контакты', 'url' => ['/contact']],
    ];
    if (!Yii::$app->user->isGuest) {
        $items[] = [
            'label' => 'Управление',
            'items' => [
                ['label' => 'Профиль', 'url' => ['/user/profile']],
                ['label' => 'Недвижимость', 'url' => ['/property/admin']],
                ['label' => 'Пользователи', 'url' => ['/user/admin']],
                Html::beginForm(['/user/logout'], 'post') .
                Html::submitButton('Выйти', ['class' => 'dropdown-item']) .
                Html::endForm(),
            ],
        ];
    }
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav ml-auto'],
        'items' => $items,
    ]);
    NavBar::end();
    ?>

    <div class="container">
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <div class="row">
            <div class="col">
                <ul class="list-unstyled">
                    <li>
                        <a href="<?= Url::to(['/property', 'PropertyListSearch[ad_type][]' => 'Аренда', 'PropertyListSearch[direction_id][]' => 17]) ?>">Аренда
                            город</a></li>
                    <li>
                        <a href="<?= Url::to(['/property', 'PropertyListSearch[ad_type][]' => 'Продажа', 'PropertyListSearch[direction_id][]' => 17]) ?>">Продажа
                            город</a></li>
                    <li><a href="<?= Url::to($outOfCityUrl) ?>">Аренда загород</a></li>
                    <li>
                        <a href="<?= Url::to(str_replace('%D0%90%D1%80%D0%B5%D0%BD%D0%B4%D0%B0', '%D0%9F%D1%80%D0%BE%D0%B4%D0%B0%D0%B6%D0%B0', $outOfCityUrl)) ?>">Продажа
                            загород</a></li>
                    <li><a href="<?= Url::to(['/direction']) ?>">Направления</a></li>
                    <li><a href="<?= Url::to(['/contacts/find']) ?>">Подобрать объект</a></li>
                    <li><a href="<?= Url::to(['/contacts/ask']) ?>">Задать вопрос</a></li>
                </ul>
            </div>
            <div class="col">
                <ul class="list-unstyled">
                    <li><a href="<?= Url::to(['/to-owners']) ?>">Владельцам</a></li>
                    <li><a href="<?= Url::to(['/design']) ?>">Строительство и дизайн</a></li>
                    <li><a href="<?= Url::to(['/advices']) ?>">Советы эксперта</a></li>
                    <li><a href="<?= Url::to(['/articles']) ?>">Статьи</a></li>
                    <li><a href="<?= Url::to(['/about']) ?>">О Нас</a></li>
                    <li><a href="<?= Url::to(['/contact']) ?>">Контакты</a></li>
                </ul>
            </div>
            <div class="col center-block text-center">
                <ul class="list-unstyled text-decoration-none">
                    <li class="h5">Оставайтесь на связи</li>
                    <li><a href="tel:+79039615031" class="h5 tel">+7 903 961 50 31</a></li>
                    <li><a href="tel:+79261660277" class="h5 tel">+7 926 166 02 77</a></li>
                    <li><a href="tel:+79167301777" class="h5 tel">+7 916 730 17 77</a></li>
                </ul>
            </div>
        </div>
        <hr>
        <p class="pull-left">&copy; Country House Realty <?= date('Y') ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
