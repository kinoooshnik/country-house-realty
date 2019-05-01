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
    <?= Yii::$app->params['yandexMetrika'] ?>
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
    ?>
    <div class="col center-block">
        <ul class="list-unstyled text-decoration-none tels">
            <li><a href="tel:<?=Yii::$app->params['phones'][0]?>" class="h6 tel"><?=Yii::$app->params['phones'][0]?></a></li>
            <li><a href="tel:<?=Yii::$app->params['phones'][1]?>" class="h6 tel"><?=Yii::$app->params['phones'][1]?></a></li>
        </ul>
    </div>
    <?php
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
                ['label' => 'Аренда', 'url' => ['/property', 'PropertyListSearch[ad_type][]' => 'Аренда']],
                ['label' => 'Продажа', 'url' => ['/property', 'PropertyListSearch[ad_type][]' => 'Продажа']],
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
                    <li><a href="<?= Url::to(['/property', 'PropertyListSearch[ad_type][]' => 'Аренда']) ?>">Аренда
                            загород</a></li>
                    <li>
                        <a href="<?= Url::to(['/property', 'PropertyListSearch[ad_type][]' => 'Продажа']) ?>">Продажа
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
                    <?php foreach (Yii::$app->params['phones'] as $phone): ?>
                        <li><a href="tel:<?= $phone ?>" class="h5 tel"><?= $phone ?></a></li>
                    <?php endforeach; ?>
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
