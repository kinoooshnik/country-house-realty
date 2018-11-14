<?php

namespace app\models\tables;

use Yii;

/**
 * This is the model class for table "property_photo".
 *
 * @property int $property_id
 * @property int $photo_id
 * @property int $position
 *
 * @property Photo $photo
 * @property Property $property
 */
class PropertyPhoto extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'property_photo';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['property_id', 'photo_id', 'position'], 'required'],
            [['property_id', 'photo_id', 'position'], 'integer'],
            [['property_id', 'photo_id'], 'unique', 'targetAttribute' => ['property_id', 'photo_id']],
            [['photo_id'], 'exist', 'skipOnError' => true, 'targetClass' => Photo::className(), 'targetAttribute' => ['photo_id' => 'id']],
            [['property_id'], 'exist', 'skipOnError' => true, 'targetClass' => Property::className(), 'targetAttribute' => ['property_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'property_id' => 'Property ID',
            'photo_id' => 'Photo ID',
            'position' => 'Position',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPhoto()
    {
        return $this->hasOne(Photo::className(), ['id' => 'photo_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProperty()
    {
        return $this->hasOne(Property::className(), ['id' => 'property_id']);
    }
}
