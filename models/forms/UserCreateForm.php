<?php

namespace app\models\forms;

use Yii;
use yii\base\Model;
use app\models\tables\User;

class UserCreateForm extends Model
{
    public $email;
    public $first_name;
    public $second_name;
    public $password;

    private $_userModel = false;

    private $returnMessageCode = null;
    private $returnMessage = null;

    function __construct(User $userModel, array $config = [])
    {
        $this->_userModel = $userModel;
        parent::__construct($config);
    }

    public function rules()
    {
        return [
            [['email', 'password'], 'required'],
            ['email', 'email'],
            [['first_name', 'second_name', 'password'], 'string'],
        ];
    }

    public function save()
    {
        if ($this->validate()) {
            $this->_userModel->email = $this->email;
            $this->_userModel->first_name = $this->first_name;
            $this->_userModel->second_name = $this->second_name;
            $this->_userModel->setNewPassword($this->password);

            if ($this->_userModel->save()) {
                Yii::$app->session->setFlash('success userCreateForm',
                    strtr(
                        'Пользователь {nameToShow} создан.<br> Данные для входа:<br>Логин: {login}<br>Пароль: {password}',
                        [
                            '{nameToShow}' => $this->_userModel->getNameToShow(),
                            '{login}' => $this->email,
                            '{password}' => $this->password,
                        ])
                );
                return true;
            }
        }
        Yii::$app->session->setFlash('error userCreateForm', 'Не удалось создать пользователя');
        return false;
    }
    public function attributeLabels()
    {
        return User::$attributeLabels;
    }
}
