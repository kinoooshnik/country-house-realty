<?php

use yii\helpers\Html;
use kartik\form\ActiveForm;

/* @var $this yii\web\View */
/* @var $userInfoForm app\models\UserInfoForm */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-info-form">

    <?php $form = ActiveForm::begin(['type' => ActiveForm::TYPE_HORIZONTAL, 'options' => ['data' => ['pjax' => true]]]); ?>

    <?= $form->field($userInfoForm, 'email')->textInput() ?>

    <?= $form->field($userInfoForm, 'first_name')->textInput() ?>

    <?= $form->field($userInfoForm, 'second_name')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
