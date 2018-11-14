<?php

namespace app\models\tables;

use Yii;
use yii\behaviors\SluggableBehavior;

/**
 * This is the model class for table "direction".
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 */
class Direction extends \yii\db\ActiveRecord
{

    public static $attributeLabels = [
        'id' => 'ID',
        'name' => 'Направление',
        'slug' => 'Slug',
    ];

    public function behaviors()
    {
        return [
            [
                'class' => SluggableBehavior::className(),
                'attribute' => 'name',
                'slugAttribute' => 'slug',
                'ensureUnique' => true,
            ],
        ];
    }

    public static function tableName()
    {
        return 'direction';
    }

    public function rules()
    {
        return [
            [['name', 'slug'], 'required'],
            [['name', 'slug'], 'string', 'max' => 255],
            [['slug'], 'unique'],
        ];
    }

    public function attributeLabels()
    {
        return self::$attributeLabels;
    }
}
