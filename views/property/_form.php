<?php

use yii\helpers\Html;
use yii\web\JsExpression;
use app\models\tables\Direction;
use kartik\form\ActiveForm;
use kartik\select2\Select2;
use kartik\widgets\FileInput;
use kartik\sortinput\SortableInput;

/* @var $this yii\web\View */
/* @var $propertyForm app\models\PropertyForm */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="property-form">

    <?php $form = ActiveForm::begin([
        'type' => ActiveForm::TYPE_HORIZONTAL,
    ]); ?>

    <?= $form->field($propertyForm, 'property_name') ?>

    <?= $form->field($propertyForm, 'property_type')->radioButtonGroup(['Дом' => 'Дом', 'Таунхаус' => 'Таунхаус', 'Квартира' => 'Квартира', 'Участок' => 'Участок']); ?>

    <?= $form->field($propertyForm, 'ad_type')->checkboxButtonGroup(['is_sale' => 'Продажа', 'is_rent' => 'Аренда'])->hint('Может быть в обоих состояниях одновременно'); ?>

    <?= $form->field($propertyForm, 'currency')->radioButtonGroup(['₽' => '₽', '$' => '$', '€' => '€']); ?>

	<?= $form->field($propertyForm, 'sale_price')->textInput() ?>
	
	<?= $form->field($propertyForm, 'rent_price')->textInput() ?>

    <?= $form->field($propertyForm, 'address')->textInput(['maxlength' => true]) ?>

    <?= $form->field($propertyForm, 'map_latitude')->textInput() ?>

    <?= $form->field($propertyForm, 'map_longitude')->textInput() ?>

    <?php $directionName = empty($propertyForm->direction_id) ? '' : Direction::findOne($propertyForm->direction_id)->name;
    echo $form->field($propertyForm, 'direction_id')->widget(Select2::classname(), [
        'initValueText' => $directionName,
        'options' => ['placeholder' => 'Поиск направления...'],
        'pluginOptions' => [
            'allowClear' => true,
            'minimumInputLength' => 0,
            'language' => [
                'errorLoading' => new JsExpression("function () { return 'Подождите...'; }"),
            ],
            'ajax' => [
                'url' => \yii\helpers\Url::to('\direction\list'),
                'dataType' => 'json',
                'data' => new JsExpression('function(params) { return {q:params.term}; }')
            ],
            'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
            'templateResult' => new JsExpression('function(direction) { return direction.text; }'),
            'templateSelection' => new JsExpression('function (direction) { return direction.text; }'),
        ],
    ]); ?>

    <?= $form->field($propertyForm, 'distance_to_mrar') ?>

    <?= $form->field($propertyForm, 'with_finishing')->radioButtonGroup(['🛇' => '🛇', '1' => 'с отделкой', '0' => 'без отделки']); ?>

    <?= $form->field($propertyForm, 'with_furniture')->radioButtonGroup(['🛇' => '🛇', '1' => 'с мебелью', '0' => 'без мебели']); ?>

    <?= $form->field($propertyForm, 'bathrooms')->radioButtonGroup(['🛇' => '🛇', 1 => '1', 2 => '2', 3 => '3', 4 => '4', 5 => '5', '> 5' => '> 5']); ?>

    <?= $form->field($propertyForm, 'bedrooms')->radioButtonGroup(['🛇' => '🛇', 1 => '1', 2 => '2', 3 => '3', 4 => '4', 5 => '5', '> 5' => '> 5']); ?>

    <?= $form->field($propertyForm, 'garage')->radioButtonGroup(['🛇' => '🛇', 0 => '0', 1 => '1', 2 => '2', 3 => '3', 4 => '4', 5 => '5', '> 5' => '> 5']); ?>

    <?= $form->field($propertyForm, 'land_area')->textInput() ?>

    <?= $form->field($propertyForm, 'build_area')->textInput() ?>

    <?= $form->field($propertyForm, 'description')->textarea(['rows' => 6]) ?>

    <?= $form->field($propertyForm, 'is_archive')->checkbox(['custom' => true])->hint('Объекты в архиве не показываются пользователю.'); ?>

    <?= $form->field($propertyForm, 'property_features')->widget(Select2::classname(), [
        'options' => ['placeholder' => 'Выберете или добавьте особенности ...', 'multiple' => true],
        'pluginOptions' => [
            'tags' => true,
            'tokenSeparators' => [',', ' '],
            'maximumInputLength' => 20,
            'minimumInputLength' => 0,
            'language' => [
                'errorLoading' => new JsExpression("function () { return 'Подождите...'; }"),
            ],
            'ajax' => [
                'url' => \yii\helpers\Url::to('\property\features-list'),
                'dataType' => 'json',
                'data' => new JsExpression('function(params) { return {q:params.term}; }')
            ],
            'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
            'templateResult' => new JsExpression('function(direction) { return direction.text; }'),
            'templateSelection' => new JsExpression('function (direction) { return direction.text; }'),
        ],
    ]) ?>

    <?php
    if (is_array($propertyForm->photos)) {
        $items = [];
        foreach ($propertyForm->photos as $key => $photo) {
            $items[$key] = ['content' =>
                Html::img("/uploads/property/original/$photo", ['height' => 100]) .
                Html::beginTag('div', ['class' => 'photo-buttons']) .
                Html::a('⮿', ['/property/delete-photo', 'photoId' => $key, 'projectId' => $propertyForm->id], [
                    'class' => 'btn btn-sm btn-danger',
                    'title' => 'Удалить',
                    'aria-label' => "Удалить",
                    'data-pjax' => true,
                    'data-method' => "post",
                    'data-confirm' => "Вы действительно хотите удалить данное фото?"
                ]) .
                Html::endTag('div')
            ];
        }
        echo $form->field($propertyForm, 'photos_sequence')->widget(SortableInput::classname(), [
            'items' => $items,
            'options' => ['class' => 'form-control', 'readonly' => false]
        ])->hint('Установите фото в порядке, в котором они будут на слайдере. Первое фото будет показано в карточке объекта.');
    } ?>

    <?= $form->field($propertyForm, 'imageFiles[]')->widget(FileInput::classname(), [
        'options' => [
            'accept' => 'image/*',
            'multiple' => true
        ],
    ]) ?>

    <div class="form-group">
        <?= Html::a('Назад', ['/property/admin'], ['class' => 'btn btn-success']); ?>
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
