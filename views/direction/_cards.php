<?php

/* @var $directionCards array */

use yii\helpers\Url;

?>
<?php foreach ($directionCards as $key => $card): ?>
    <?= $key % 3 == 0 ? '<div class="card-deck">' : '' ?>
    <div class="card mb-3 card-text-overlay d-flex justify-content-center"
         onclick="location.href='<?= Url::to(['/property', 'PropertyListSearch[direction_id][]' => $card['direction']->id]) ?>
                 ';" style="cursor:pointer">
        <img class="card-img-top direction-card-img"
             src="<?= isset($card['photo']) ? Yii::getAlias('@propertyOpiginalPhotoUploadDir/') . $card['photo']->name : '' ?>"
             alt="<?= $card['direction']->name ?>">
        <div class="card-body d-flex">
            <h4 class="card-title text-white align-items-center">
                <?= $card['direction']->name ?>
            </h4>
        </div>
    </div>
    <?= ($key + 1) % 3 == 0 || (count($directionCards) - 1) == $key ? '</div>' : '' ?>
<?php endforeach; ?>