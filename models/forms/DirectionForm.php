<?php

namespace app\models\forms;

use Yii;
use yii\base\Model;

class DirectionForm extends Model
{
    public $name;

    private $_directionModel;

    function __construct(Direction $directionModel, array $config = [])
    {
        $this->_directionModel = $directionModel;
        parent::__construct($config);
    }

    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string'],
        ];
    }

    public function save()
    {
        if ($this->validate()) {
            $this->_directionModel->name = $this->name;

            if ($this->_directionModel->save()) {
                Yii::$app->session->setFlash('success',
                    strtr(
                        'Направление {name} сохраннено.',
                        [
                            '{name}' => $this->name,
                        ]));
                return true;
            }
        }
        Yii::$app->session->setFlash('error', 'Не удалось сохранить направление.');
        return false;
    }
    public function attributeLabels()
    {
        return Direction::$attributeLabels;
    }
}
