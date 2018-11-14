<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\models\User;

class UserNewPassForm extends Model
{
    public $password;
//    public $password_repeat;

    private $_userModel = false;

    private $returnMessageCode = null;
    private $returnMessage = null;

    public function __construct(User $userModel, array $config = [])
    {
        $this->_userModel = $userModel;
        parent::__construct($config);
    }

    public function rules()
    {
        return [
            [['password'], 'required'],
//            ['password', 'compare', 'compareAttribute' => 'password_repeat'],
        ];
    }

    public function save()
    {
        if ($this->validate()) {
            $this->_userModel->setNewPassword($this->password);

            if ($this->_userModel->save()) {
                Yii::$app->session->setFlash(
                     'success passwordForm',
                    strtr(
                        'Пароль успешно изменен.<br> Новые данные для входа:<br>Логин: {login}<br>Пароль: {password}',
                        [
                            '{login}' => $this->_userModel->email,
                            '{password}' => $this->password,
                        ]
                    )
                );
                return true;
            }
        }
        Yii::$app->session->setFlash('error passwordForm', 'Не удалось изменить пароль');
        return false;
    }

    public function attributeLabels()
    {
        return array_merge(
            User::$attributeLabels,
            [
                'password_repeat' => 'Повторите пароль',
            ]
        );
    }
}
