<?php

namespace app\models;

use yii\base\Exception;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string $email
 * @property string $first_name
 * @property string $second_name
 * @property string $auth_key
 * @property string $password_hash
 */
class User extends ActiveRecord implements \yii\web\IdentityInterface
{
    public static $attributeLabels = [
        'email' => 'Email',
        'first_name' => 'Имя',
        'second_name' => 'Фамилия',
        'password' => 'Новый пароль',
    ];

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
            ],
        ];
    }

    public static function tableName()
    {
        return 'user';
    }

    public function rules()
    {
        return [
            [['email', 'password_hash'], 'required'],
            [['email', 'first_name', 'second_name', 'auth_key', 'password_hash'], 'string', 'max' => 255],
            [['email'], 'unique'],
            [['email'], 'email'],
        ];
    }

    public static function findIdentity($id)
    {
        $user = self::find()
            ->where('id=:id')
            ->addParams([':id' => $id])
            ->one();
        return $user;
    }

    public function attributeLabels()
    {
        return self::$attributeLabels;
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        return null;
    }

    public static function findUser($email, $password)
    {
        $user = self::find()
            ->where('email=:email')
            ->addParams([':email' => $email])
            ->one();

        if (isset($user) && $user->validatePassword($password)) {
            return $user;
        }
        return null;
    }

    public static function registrationUser($email, $password)
    {
        $user = new self();
        $user->email = $email;
        $user->setNewPassword($password);
        if ($user->validate()) {
            $user->save();
            return $user;
        } else return new Exception('Registration error');
    }

    public function getId()
    {
        return $this->id;
    }

    public function getAuthKey()
    {
        return $this->auth_key;
    }

    public function getNameToShow()
    {
        if (!empty($this->first_name) || !empty($this->second_name)) {
            return $this->first_name . ' ' . $this->second_name;
        }
        return $this->email;
    }

    public function validateAuthKey($authKey)
    {
        return $this->auth_key === $authKey;
    }

    public function validatePassword($password)
    {
        return \Yii::$app->getSecurity()->validatePassword($password, $this->password_hash);
    }

    public function setNewPassword($password)
    {
        $this->password_hash = \Yii::$app->getSecurity()->generatePasswordHash($password);
    }
}
