<?php

namespace app\models\forms;

use Yii;
use yii\base\Model;
use app\models\tables\User;

class LoginForm extends Model
{
    public $email;
    public $password;

    private $_user = null;

    public function rules()
    {
        return [
            [['email', 'password'], 'required'],
            ['email', 'email'],
        ];
    }

    public function login()
    {
        if ($this->getUser()) {
            return Yii::$app->user->login($this->getUser(), 3600 * 24 * 30);
        }
        return false;
    }

    public function getUser()
    {
        if (!isset($this->_user)) {
            $this->_user = User::findUser($this->email, $this->password);
        }
        return $this->_user;
    }
}
