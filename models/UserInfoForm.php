<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\models\User;

class UserInfoForm extends Model implements \app\models\ReturnMessageInterface
{
    public $email;
    public $first_name;
    public $second_name;

    private $_userModel = false;

    private $returnMessageCode = null;
    private $returnMessage = null;

    function __construct(User $userModel, array $config = [])
    {
        $this->_userModel = $userModel;

        if (!$this->_userModel->isNewRecord) {
            $this->loadAttributes();
        }

        parent::__construct($config);
    }

    public function rules()
    {
        return [
            [['email'], 'required'],
            ['email', 'email'],
            [['first_name', 'second_name'], 'string'],
        ];
    }

    public function loadAttributes()
    {
        $this->email = $this->_userModel->email;
        $this->first_name = $this->_userModel->first_name;
        $this->second_name = $this->_userModel->second_name;
    }

    public function save()
    {
        if ($this->validate()) {
            $this->_userModel->email = $this->email;
            $this->_userModel->first_name = $this->first_name;
            $this->_userModel->second_name = $this->second_name;

            if ($this->_userModel->save()) {
                $this->setReturnMessage('success', 'Данные успешно обнавлены');
                return true;
            }
        }
        $this->setReturnMessage('error', 'Не удалось обновить данные');
        return false;
    }

    public function attributeLabels()
    {
        return User::$attributeLabels;
    }

    public function setReturnMessage($code, $message)
    {
        $this->returnMessageCode = $code;
        $this->returnMessage = $message;
    }

    public function getReturnMessageCode()
    {
        return $this->returnMessageCode;
    }

    public function getReturnMessage()
    {
        return $this->returnMessage;
    }
}
