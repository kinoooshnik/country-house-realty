<?php

use yii\helpers\Html;
use kartik\form\ActiveForm;

/* @var $this yii\web\View */
/* @var $userNewPassForm app\models\UserNewPassForm */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-pass-form">

    <?php $form = ActiveForm::begin(['type' => ActiveForm::TYPE_HORIZONTAL, 'options' => ['data' => ['pjax' => true]]]); ?>

    <?= $form->field($userNewPassForm, 'password', [
        'addon' => [
            'append' => [
                'content' => Html::Button('Сгенерировать', [
                    'class' => 'btn btn-outline-secondary',
                    'onclick' => 'gen_password(\'usercreateform-password\', \'usercreateform-password-repeat\')'
                ]),
                'asButton' => true
            ]
        ]
    ])->passwordInput(['id' => 'usercreateform-password',]);
    ?>
    <?= $form->field($userNewPassForm, 'password_repeat', [
    ])->passwordInput(['id' => 'usercreateform-password-repeat',]);
    ?>
    <script>
        function gen_password(fieldId, fieldRepeatId) {
            var password = "";
            var symbols = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789!№;%:?*()_+=";
            for (var i = 0; i < 15; i++) {
                password += symbols.charAt(Math.floor(Math.random() * symbols.length));
            }
            document.getElementById(fieldId).value = password;
            document.getElementById(fieldRepeatId).value = password;
        }
    </script>

    <div class="form-group">
        <?= Html::submitButton('Изменить пароль', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
