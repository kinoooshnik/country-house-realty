<?php

use yii\helpers\Html;
use yii\widgets\Pjax;
use app\widgets\Alert;

/* @var $this yii\web\View */
/* @var $userInfoForm app\models\UserInfoForm */
/* @var $userNewPassForm app\models\UserNewPassForm */
/* @var $userModel app\models\User */

$this->title = 'Профиль пользователя ' . $userModel;
?>
<div class="user-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php Pjax::begin(['enablePushState' => false]); ?>
    <?= Alert::echoAlertFromReturnMessage($userInfoForm) ?>
    <?= $this->render('_userInfoForm', [
        'userInfoForm' => $userInfoForm,
    ]) ?>
    <?php Pjax::end(); ?>


    <?php Pjax::begin(['enablePushState' => false]); ?>
    <?= Alert::echoAlertFromReturnMessage($userNewPassForm) ?>
    <?= $this->render('_userNewPassForm', [
        'userNewPassForm' => $userNewPassForm,
    ]) ?>
    <?php Pjax::end(); ?>

</div>
