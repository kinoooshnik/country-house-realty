<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\form\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\PropertySearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="property-search">
	<?php $form = ActiveForm::begin([
        'method' => 'get',
        'action' => Url::current([]),
    ]); ?>
	<div class="container px-0">
		<div class="row align-items-center justify-content-md-center">
			<div class="col-md-auto">
				<?php
                $field = $form->field($model, 'property_type')->checkboxButtonGroup(['Дом' => 'Дом', 'Таунхаус' => 'Таунхаус', 'Квартира' => 'Квартира', 'Участок' => 'Участок']);
                $field->template = '{label}<br>{input}';
                echo $field;
            ?>
			</div>

			<div class="col-md-auto">
				<?php
                $field = $form->field($model, 'ad_type')->checkboxButtonGroup(['Продажа' => 'Продажа', 'Аренда' => 'Аренда']);
                $field->template = '{label}<br>{input}';
                echo $field;
            ?>
			</div>
			<div class="col-md-auto">
				<label>Цена</label>
				<div class="d-flex flex-row">
					<div class="pr-1">
						<?php
                        $field = $form->field($model, 'price_from')->textInput(['class' => 'price', 'placeholder' => 'Цена от']);
                        $field->enableClientValidation = false;
                        $field->template = '{input}';
                        echo $field;
                    ?>
					</div>
					<div class="px-1">
						<?php
                        $field = $form->field($model, 'price_to')->textInput(['class' => 'price', 'placeholder' => 'До']);
                        $field->enableClientValidation = false;
                        $field->template = '{input}';
                        echo $field;
                    ?>
					</div>
					<div class="pl-1">
						<?php
                        $field = $form->field($model, 'currency')->radioButtonGroup(['₽' => '₽', '$' => '$', '€' => '€']);
                        $field->template = '{input}';
                        echo $field;
                    ?>
					</div>
				</div>
			</div>
		</div>
	</div>


	<?php //$form->field($model, 'distance_to_mrar')?>

	<?php //$form->field($model, 'with_finishing')?>

	<?php //$form->field($model, 'with_furniture')?>

	<?php //$form->field($model, 'bathrooms')?>

	<?php //$form->field($model, 'bedrooms')?>

	<?php //$form->field($model, 'garage')?>

	<?php //$form->field($model, 'land_area')?>

	<?php //$form->field($model, 'build_area')?>

	<?php //$form->field($model, 'direction')?>

	<?php //$form->field($model, 'property_features')?>

	<div class="form-group mb-4">
		<div class="row justify-content-center">
			<?= Html::submitButton('Показать', ['class' => 'btn btn-primary mx-2']) ?>
			<?= Html::a('Сбросить', Url::to(['/property']), ['class' => 'btn btn-default  mx-2']) ?>
		</div>
	</div>

	<?php ActiveForm::end(); ?>

</div>