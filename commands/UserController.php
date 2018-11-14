<?php
namespace app\commands;

use yii\console\Controller;
use yii\console\ExitCode;
use app\models\tables\User;

class UserController extends Controller
{
    public function actionRegistration($email, $password)
    {
        if(User::registrationUser($email, $password)) {
            return ExitCode::OK;
        }
    }
}