<?php

use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $propertyViews [] app\models\views\PropertyView */
/* @var $propertySearchModel app\models\search\PropertyListSearch */
/* @var $propertyCount integer */
/* @var $directionList[] */
/* @var $nav array */

$this->title = 'Список недвижимости | County House - Элитная недвижимость в Москве и МО';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="property-index">
    <h1 class="mb-4 h2" style="text-align: center">Аренда и продажа элитной недвижимости в Москва и МО
        <small class="text-muted"><?= $propertyCount ?></small>
    </h1>

    <?= $this->render('_search', [
        'model' => $propertySearchModel,
        'directionList' => $directionList
    ]); ?>

    <?= $this->render('_cards', [
        'propertyViews' => $propertyViews,
    ]) ?>

    <?php if (count($propertyViews)): ?>
        <div class="container px-0">
            <div class="d-flex justify-content-center">
                <nav aria-label="...">
                    <ul class="pagination">
                        <li class="page-item<?= $nav['currentPage'] == 1 ? ' disabled' : '' ?>">
                            <a class="page-link" href="<?= Url::current(['page' => ($nav['currentPage'] - 1)]) ?>"
                               aria-label="Previous">
                                <span aria-hidden="true">&laquo;</span>
                                <span class="sr-only">Previous</span>
                            </a>
                        </li>
                        <?php if ($nav['currentPage'] >= 3): ?>
                            <li class="page-item"><a class="page-link"
                                                     href="<?= Url::current(['page' => 1]) ?>"><?= 1 ?></a></li>
                        <?php endif; ?>
                        <?php if ($nav['currentPage'] > 3): ?>
                            <li class="page-item disabled">
                                <a class="page-link" href="#" aria-label="Center">
                                    <span aria-hidden="true">...</span>
                                    <span class="sr-only">...</span>
                                </a>
                            </li>
                        <?php endif; ?>
                        <?php if ($nav['currentPage'] != 1): ?>
                            <li class="page-item"><a class="page-link"
                                                     href="<?= Url::current(['page' => $nav['currentPage'] - 1]) ?>"><?= $nav['currentPage'] - 1 ?></a>
                            </li>
                        <?php endif; ?>
                        <li class="page-item active">
                            <a class="page-link"
                               href="<?= Url::current(['page' => $nav['currentPage']]) ?>"><?= $nav['currentPage'] ?>
                                <span
                                        class="sr-only">(current)</span></a>
                        </li>
                        <?php if ($nav['currentPage'] != $nav['pageCount']): ?>
                            <li class="page-item"><a class="page-link"
                                                     href="<?= Url::current(['page' => $nav['currentPage'] + 1]) ?>"><?= $nav['currentPage'] + 1 ?></a>
                            </li>
                        <?php endif; ?>
                        <?php if ($nav['currentPage'] + 2 < $nav['pageCount']): ?>
                            <li class="page-item disabled">
                                <a class="page-link" href="#" aria-label="Center">
                                    <span aria-hidden="true">...</span>
                                    <span class="sr-only">...</span>
                                </a>
                            </li>
                        <?php endif; ?>
                        <?php if ($nav['currentPage'] + 2 <= $nav['pageCount']): ?>
                            <li class="page-item"><a class="page-link"
                                                     href="<?= Url::current(['page' => $nav['pageCount']]) ?>"><?= $nav['pageCount'] ?></a>
                            </li>
                        <?php endif; ?>
                        <li class="page-item<?= $nav['currentPage'] == $nav['pageCount'] ? ' disabled' : '' ?>">
                            <a class="page-link" href="<?= Url::current(['page' => $nav['currentPage'] + 1]) ?>"
                               aria-label="Next">
                                <span aria-hidden="true">&raquo;</span>
                                <span class="sr-only">Next</span>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    <?php endif; ?>
</div>