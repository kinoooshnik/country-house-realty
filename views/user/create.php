<?php

use yii\helpers\Html;
use kartik\form\ActiveForm;
use yii\widgets\Pjax;
use app\widgets\Alert;

/* @var $this yii\web\View */
/* @var $userCreateForm app\models\userCreateForm */

$this->title = 'Создание пользователя';
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-create">

    <h1><?= Html::encode($this->title) ?></h1>

	<?php Pjax::begin(['enablePushState' => false]); ?>
	
	<?= Alert::widget(['tag' => 'userCreateForm']) ?>

    <?php $form = ActiveForm::begin(['type' => ActiveForm::TYPE_HORIZONTAL,]); ?>

    <?= $form->field($userCreateForm, 'email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($userCreateForm, 'first_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($userCreateForm, 'second_name')->textInput(['maxlength' => true]) ?>

    <?php
    echo $form->field($userCreateForm, 'password', [
        'addon' => [
            'append' => [
                'content' => Html::Button('Сгенерировать', [
                    'class' => 'btn btn-outline-secondary',
                    'onclick' => 'gen_password(\'usercreateform-password\')'
                ]),
                'asButton' => true
            ]
        ]
    ])->passwordInput(['id' => 'usercreateform-password',]);
    ?>
    <script>
        function gen_password(fieldId) {
            var password = "";
            var symbols = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789!№;%:?*()_+=";
            for (var i = 0; i < 15; i++) {
                password += symbols.charAt(Math.floor(Math.random() * symbols.length));
            }
            document.getElementById(fieldId).value = password;
        }
    </script>

    <div class="form-group">
        <?= Html::submitButton('Создать', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
    <?php Pjax::end(); ?>

</div>
