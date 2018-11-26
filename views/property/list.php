<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $propertyViews app\models\views\PropertyView */
/* @var $propertySearchModel app\models\search\PropertyListSearch */
/* @var $nav array */

$this->title = 'County House - Элитная недвижимость в Москве и МО';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="property-index">
	<h1 class="mb-4 h2" style="text-align: center">Аренда и продажа элитной недвижимости в Москва и МО</h1>

	<?php /* $this->render('_search', [
        'model' => $propertySearchModel
    ]);*/ ?>

	<?= $this->render('_cards', [
        'propertyViews' => $propertyViews,
    ]) ?>

	<div class="container px-0">
		<div class="d-flex flex-row">
			<nav aria-label="...">
				<ul class="pagination">
					<li class="page-item<?= $nav['currentPage'] == 1 ? ' disabled' : '' ?>">
						<a class="page-link" href="<?= Url::to(['', 'page' => $nav['currentPage'] - 1]) ?>"
						 aria-label="Previous">
							<span aria-hidden="true">&laquo;</span>
							<span class="sr-only">Previous</span>
						</a>
					</li>
					<?php if ($nav['currentPage'] >= 3): ?>
					<li class="page-item"><a class="page-link" href="<?= Url::to(['', 'page' => 1]) ?>"><?= 1 ?></a></li>
					<?php endif;?>
					<?php if ($nav['currentPage'] > 3): ?>
					<li class="page-item disabled">
						<a class="page-link" href="#" aria-label="Center">
							<span aria-hidden="true">...</span>
							<span class="sr-only">...</span>
						</a>
					</li>
					<?php endif;?>
					<?php if ($nav['currentPage'] != 1): ?>
					<li class="page-item"><a class="page-link" href="<?= Url::to(['', 'page' => $nav['currentPage'] - 1]) ?>"><?= $nav['currentPage'] - 1 ?></a></li>
					<?php endif;?>
					<li class="page-item active">
						<a class="page-link" href="<?= Url::to(['', 'page' => $nav['currentPage']]) ?>"><?= $nav['currentPage'] ?><span
							 class="sr-only">(current)</span></a>
					</li>
					<?php if ($nav['currentPage'] != $nav['pageCount']): ?>
					<li class="page-item"><a class="page-link" href="<?= Url::to(['', 'page' => $nav['currentPage'] + 1]) ?>"><?= $nav['currentPage'] + 1 ?></a></li>
					<?php endif;?>
					<?php if ($nav['currentPage'] + 2 < $nav['pageCount']): ?>
					<li class="page-item disabled">
						<a class="page-link" href="#" aria-label="Center">
							<span aria-hidden="true">...</span>
							<span class="sr-only">...</span>
						</a>
					</li>
					<?php endif;?>
					<?php if ($nav['currentPage'] + 2 <= $nav['pageCount']): ?>
					<li class="page-item"><a class="page-link" href="<?= Url::to(['', 'page' => $nav['pageCount']]) ?>"><?= $nav['pageCount'] ?></a></li>
					<?php endif;?>
					<li class="page-item<?= $nav['currentPage'] == $nav['pageCount'] ? ' disabled' : '' ?>">
						<a class="page-link" href="<?= Url::to(['', 'page' => $nav['currentPage'] + 1]) ?>"
						 aria-label="Next">
							<span aria-hidden="true">&raquo;</span>
							<span class="sr-only">Next</span>
						</a>
					</li>
				</ul>
			</nav>
		</div>
	</div>
</div>