<?php

/* @var $this \yii\web\View */

/* @var $content string */

use app\widgets\Alert;
use yii\helpers\Html;
use yii\bootstrap4\Nav;
use yii\bootstrap4\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

AppAsset::register($this);
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
    <script defer src="https://use.fontawesome.com/releases/v5.3.1/js/all.js" crossorigin="anonymous"></script>
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
    $items = [
        ['label' => 'Все объекты', 'url' => ['/property']],
        [
            'label' => 'Город',
            'items' => [
                ['label' => 'Аренда', 'url' => ['/property/city/rent']],
                ['label' => 'Продажа', 'url' => ['/property/city/sale']],
                '<div class="dropdown-divider"></div>',
                ['label' => 'Метро', 'url' => ['/property/city/#subway']],
                ['label' => 'ЖК', 'url' => ['/property/city/#hc']],
            ],
        ],
        [
            'label' => 'Загород',
            'items' => [
                ['label' => 'Аренда', 'url' => ['/property/out-of-city/rent']],
                ['label' => 'Продажа', 'url' => ['/property/out-of-city/sale']],
                '<div class="dropdown-divider"></div>',
                ['label' => 'Направление', 'url' => ['/property/out-of-city/#directions']],
                ['label' => 'Поселок', 'url' => ['/property/out-of-city/#village']],
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
        <p class="pull-left">&copy; Country House <?= date('Y') ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
