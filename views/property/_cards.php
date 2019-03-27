<?php

use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $propertyViews app\models\views\PropertyView */
while (count($propertyViews) % 3 != 0) {
    $propertyViews[] = null;
}
?>
<?php foreach ($propertyViews as $key => $propertyView): ?>
    <?= $key % 3 == 0 ? '<div class="card-deck">' : '' ?>
    <div class="card mb-3<?= $propertyView == null ? ' border-white' : '' ?>">
        <?php if ($propertyView != null): ?>
            <?php $url = Url::to(['/property/view', 'slug' => $propertyView->property_slug]); ?>
            <a href="<?= $url ?>"><img class="card-img-top"
                                       src="<?= $propertyView->photos[0]['photoPath'] ?>"
                                       alt="<?= $propertyView->property_name ?>"></a>
            <div class="card-body">
                <h5 class="card-title"><a href="<?= $url ?>">
                        <?= $propertyView->property_name ?></a></h5>
                <div class="d-flex flex-row">
                    <?php if ($propertyView->is_sale): ?>
                        <div class="d-flex flex-column">
                            <small class="text-muted">Продажа</small>
                            <div class="h5">
                                <?= number_format($propertyView->sale_price, 0, '.', ' ') . ' ' . $propertyView->currency ?>
                            </div>
                        </div>
                    <?php endif; ?>
                    <?php if ($propertyView->is_sale && $propertyView->is_rent): ?>
                        <div class="d-flex align-items-end">
                            <div class="h5">&nbsp;&nbsp;|&nbsp;&nbsp;</div>
                        </div>
                    <?php endif; ?>
                    <?php if ($propertyView->is_rent): ?>
                        <div class="d-flex flex-column">
                            <small class="text-muted">Аренда</small>
                            <div class="h5">
                                <?= number_format($propertyView->rent_price, 0, '.', ' ') . ' ' . $propertyView->currency ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="d-flex flex-column">
                    <div class="d-flex flex-row small text-muted">
                        <?= $propertyView->property_type ?>
                        <?= !empty($propertyView->build_area) ? ' | ' . $propertyView->build_area . ' м²' : '' ?>
                        <?= !empty($propertyView->land_area) ? ' | ' . $propertyView->land_area . ' сот.' : '' ?>
                    </div>
                    <div class="d-flex flex-row small text-muted">
                        <?= !empty($propertyView->direction) ? $propertyView->direction['name'] : '' ?>
                        <?= !empty($propertyView->distance_to_mrar) ? ' | ' . $propertyView->distance_to_mrar . ' км.' : '' ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
    <?= ($key + 1) % 3 == 0 || $key == count($propertyViews) - 1 ? '</div>' : '' ?>
<?php endforeach;
