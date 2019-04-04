<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\form\ActiveForm;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model app\models\search\PropertyListSearch */
/* @var $directionList array */
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
        <div id="additionalFilters" <?php
        if (empty($model->direction_id) && empty($model->build_area_from) && empty($model->build_area_to) &&
            empty($model->land_area_from) && empty($model->land_area_to) && empty($model->with_finishing) &&
            empty($model->with_furniture) && empty($model->distance_to_mrar_from) && empty($model->distance_to_mrar_to) &&
            empty($model->bedrooms) && empty($model->garage) && empty($model->bathrooms)) {
            echo 'style="display:none"';
        }
        ?>>
            <div class="row align-items-center justify-content-md-center">
                <div class="col-md-auto" style="width: 300px">
                    <?php
                    $field = $form->field($model, 'direction_id')->widget(Select2::className(), [
                        'class' => 'additFilt',
                        'data' => $directionList,
                        'options' => ['placeholder' => 'Выберете направления...', 'multiple' => true],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ])->label('Направления');
                    $field->template = '{label}<br>{input}';
                    echo $field;
                    ?>
                </div>

                <div class="col-md-auto">
                    <label>Жилая площадь</label>
                    <div class="d-flex flex-row">
                        <div class="pr-1">
                            <?php
                            $field = $form->field($model, 'build_area_from')->textInput(['class' => 'additFilt', 'style' => 'max-width: 80px', 'placeholder' => 'От м²']);
                            $field->enableClientValidation = false;
                            $field->template = '{input}';
                            echo $field;
                            ?>
                        </div>
                        <div class="px-1">
                            <?php
                            $field = $form->field($model, 'build_area_to')->textInput(['class' => 'additFilt', 'style' => 'max-width: 80px', 'placeholder' => 'До м²']);
                            $field->enableClientValidation = false;
                            $field->template = '{input}';
                            echo $field;
                            ?>
                        </div>
                    </div>
                </div>

                <div class="col-md-auto">
                    <label>Площадь участка</label>
                    <div class="d-flex flex-row">
                        <div class="pr-1">
                            <?php
                            $field = $form->field($model, 'land_area_from')->textInput(['class' => 'additFilt', 'style' => 'max-width: 80px', 'placeholder' => 'От сот.']);
                            $field->enableClientValidation = false;
                            $field->template = '{input}';
                            echo $field;
                            ?>
                        </div>
                        <div class="px-1">
                            <?php
                            $field = $form->field($model, 'land_area_to')->textInput(['class' => 'additFilt', 'style' => 'max-width: 80px', 'placeholder' => 'До сот.']);
                            $field->enableClientValidation = false;
                            $field->template = '{input}';
                            echo $field;
                            ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row align-items-center justify-content-md-center">
                <div class="col-md-auto">
                    <?php
                    $field = $form->field($model, 'with_finishing')->checkboxButtonGroup(['C отделкой' => 'С отделкой', 'Без отделки' => 'Без отделки'], ['class' => 'additFilt',]);
                    $field->template = '{label}<br>{input}';
                    echo $field;
                    ?>
                </div>

                <div class="col-md-auto">
                    <?php
                    $field = $form->field($model, 'with_furniture')->checkboxButtonGroup(['C мебелью' => 'C мебелью', 'Без мебели' => 'Без мебели'], ['class' => 'additFilt',]);
                    $field->template = '{label}<br>{input}';
                    echo $field;
                    ?>
                </div>

                <div class="col-md-auto">
                    <label>Расстояние до МКАД</label>
                    <div class="d-flex flex-row">
                        <div class="pr-1">
                            <?php
                            $field = $form->field($model, 'distance_to_mrar_from')->textInput(['class' => 'additFilt', 'style' => 'max-width: 80px', 'placeholder' => 'От км.']);
                            $field->enableClientValidation = false;
                            $field->template = '{input}';
                            echo $field;
                            ?>
                        </div>
                        <div class="px-1">
                            <?php
                            $field = $form->field($model, 'distance_to_mrar_to')->textInput(['class' => 'additFilt', 'style' => 'max-width: 80px', 'placeholder' => 'До км.']);
                            $field->enableClientValidation = false;
                            $field->template = '{input}';
                            echo $field;
                            ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row align-items-center justify-content-md-center" id="wwww1">
                <div class="col-md-auto">
                    <?php
                    $field = $form->field($model, 'bedrooms')->checkboxButtonGroup(['1' => '1', '2' => '2', '3' => '3', '4' => '4', '5' => '5', '> 5' => '> 5'], ['class' => 'additFilt',])->label('Количество спален');
                    $field->template = '{label}<br>{input}';
                    echo $field;
                    ?>
                </div>

                <div class="col-md-auto">
                    <?php
                    $field = $form->field($model, 'garage')->checkboxButtonGroup(['0' => '0', '1' => '1', '2' => '2', '3' => '3', '4' => '4', '5' => '5', '> 5' => '> 5'], ['class' => 'additFilt',])->label('Количество мест для машин');
                    $field->template = '{label}<br>{input}';
                    echo $field;
                    ?>
                </div>

                <div class="col-md-auto">
                    <?php
                    $field = $form->field($model, 'bathrooms')->checkboxButtonGroup(['1' => '1', '2' => '2', '3' => '3', '4' => '4', '5' => '5', '> 5' => '> 5'], ['class' => 'additFilt',])->label('Количество ванных комнат');
                    $field->template = '{label}<br>{input}';
                    echo $field;
                    ?>
                </div>
            </div>
        </div>
    </div>

    <div class="form-group mb-4">
        <div class="row justify-content-center">
            <?= Html::submitButton('Показать', ['class' => 'btn btn-primary mx-2']) ?>
            <button type="button" id="additFiltButt" class="btn btn-outline-primary mx-2">Еще</button>
            <?= Html::a('Сбросить', Url::to(['/property']), ['class' => 'btn btn-outline-dark mx-2']) ?>

        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>