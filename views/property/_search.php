<?php

use yii\helpers\Html;
use kartik\form\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\PropertySearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="property-search">

	<?php $form = ActiveForm::begin([
        'method' => 'get',
    ]); ?>

	<div class="dropdown">
		<button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown"
		 aria-haspopup="true" aria-expanded="false">
			Тип недвижимости
		</button>
		<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
			<span class="dropdown-item-text">
				<?php
                    // $field = $form->field($model, 'property_type')->checkboxList(['Дом' => 'Дом', 'Таунхаус' => 'Таунхаус', 'Квартира' => 'Квартира', 'Участок' => 'Участок'], ['custom' => true]);
                    // $field->template = '{input}';
                    // echo $field;
                ?>

				<div class="custom-control custom-checkbox">
					<input type="hidden" name="PropertyListSearch[property_type]" value="0">
					<input type="checkbox" id="propertylistsearch-property_type" class="custom-control-input" name="PropertyListSearch[property_type]"
					 value="1" aria-invalid="false">
					<label class="has-star custom-control-label" for="propertylistsearch-property_type">Тип недвижимости</label>
				</div>
				<?= $form->field($model, 'property_type')->radioList(['Дом' => 'Дом', 'Таунхаус' => 'Таунхаус', 'Квартира' => 'Квартира', 'Участок' => 'Участок']) ?>

				<?= $form->field($model, 'property_type') ?>
				<div>
					<input type="checkbox" id="scales" name="scales" checked>
					<label for="scales">Scales</label>
				</div>
			</span>
				<form class="px-4 py-3">
					<div class="form-group">
						<label for="exampleDropdownFormEmail1">Email address</label>
						<input type="email" class="form-control" id="exampleDropdownFormEmail1" placeholder="email@example.com">
					</div>
					<div class="form-group">
						<label for="exampleDropdownFormPassword1">Password</label>
						<input type="password" class="form-control" id="exampleDropdownFormPassword1" placeholder="Password">
					</div>
					<div class="form-check">
						<input type="checkbox" class="form-check-input" id="dropdownCheck">
						<label class="form-check-label" for="dropdownCheck">
							Remember me
						</label>
					</div>
					<button type="submit" class="btn btn-primary">Sign in</button>
				</form>
				<div class="dropdown-divider"></div>
				<a class="dropdown-item" href="#">New around here? Sign up</a>
				<a class="dropdown-item" href="#">Forgot password?</a>

		</div>
	</div>
	<?php
                    // $field = $form->field($model, 'property_type')->checkboxList(['Дом' => 'Дом', 'Таунхаус' => 'Таунхаус', 'Квартира' => 'Квартира', 'Участок' => 'Участок'], ['custom' => true]);
                    // $field->template = '{input}';
                    // echo $field;
                ?>
	<?php
                
$data = [0 => 'Sun', 1 => 'Mon', 2 => 'Tue', 3 => 'Wed', 4 => 'Thu', 5 => 'Fri', 6 => 'Sat'];
 
// Simple basic usage
// echo $form->field($model, 'property_type')->checkboxButtonGroup($data);?>

	<div class="custom-control custom-checkbox custom-control-inline">
		<input type="checkbox" id="custom-checkbox-list-inline-2" class="custom-control-input" name="Demo[check_3][]" value="2"
		 data-index="2" labeloptions="{&quot;class&quot;:&quot;custom-control-label&quot;}">
		<label class="custom-control-label" for="custom-checkbox-list-inline-2">Evening</label>
	</div>

	<div class="custom-control custom-checkbox">
		<input type="hidden" name="PropertyListSearch[property_type]" value="0">
		<input type="checkbox" id="propertylistsearch-property_type" class="custom-control-input" name="PropertyListSearch[property_type]"
		 value="1" aria-invalid="false">
		<label class="has-star custom-control-label" for="propertylistsearch-property_type">Тип недвижимости</label>
	</div>

	<div class="custom-control custom-checkbox">
		<input type="hidden" name="PropertyForm[is_archive]" value="0">
		<input type="checkbox" id="propertyform-is_archive" class="custom-control-input" name="PropertyForm[is_archive]"
		 value="1" aria-invalid="false">
		<label class="has-star custom-control-label" for="propertyform-is_archive">В архиве</label>
	</div>


	<?= $form->field($model, 'ad_type') ?>

	<?= $form->field($model, 'currency') ?>

	<?= $form->field($model, 'price_from') ?>

	<?= $form->field($model, 'price_to') ?>

	<?= $form->field($model, 'distance_to_mrar') ?>

	<?= $form->field($model, 'with_finishing') ?>

	<?= $form->field($model, 'with_furniture') ?>

	<?= $form->field($model, 'bathrooms') ?>

	<?= $form->field($model, 'bedrooms') ?>

	<?= $form->field($model, 'garage') ?>

	<?= $form->field($model, 'land_area') ?>

	<?= $form->field($model, 'build_area') ?>

	<?= $form->field($model, 'direction') ?>

	<?= $form->field($model, 'property_features') ?>

	<div class="form-group">
		<?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
		<?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
	</div>

	<?php ActiveForm::end(); ?>

</div>