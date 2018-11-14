<?php

namespace app\models\tables;

use Yii;

/**
 * This is the model class for table "property_property_features".
 *
 * @property int $property_id
 * @property int $property_features_id
 *
 * @property PropertyFeatures $propertyFeatures
 * @property Property $property
 */
class PropertyPropertyFeatures extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'property_property_features';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['property_id', 'property_features_id'], 'required'],
            [['property_id', 'property_features_id'], 'integer'],
            [['property_id', 'property_features_id'], 'unique', 'targetAttribute' => ['property_id', 'property_features_id']],
            [['property_features_id'], 'exist', 'skipOnError' => true, 'targetClass' => PropertyFeatures::className(), 'targetAttribute' => ['property_features_id' => 'id']],
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
            'property_features_id' => 'Property Features ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPropertyFeatures()
    {
        return $this->hasOne(PropertyFeatures::className(), ['id' => 'property_features_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProperty()
    {
        return $this->hasOne(Property::className(), ['id' => 'property_id']);
    }
}
