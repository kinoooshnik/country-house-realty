<?php

namespace app\models\tables;

use Yii;
use yii\behaviors\SluggableBehavior;

/**
 * This is the model class for table "property_features".
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 *
 * @property PropertyPropertyFeatures[] $propertyPropertyFeatures
 * @property Property[] $properties
 */
class PropertyFeatures extends \yii\db\ActiveRecord
{
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
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'property_features';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'slug'], 'required'],
            [['name', 'slug'], 'string', 'max' => 255],
            [['name'], 'unique'],
            [['slug'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'slug' => 'Slug',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPropertyPropertyFeatures()
    {
        return $this->hasMany(PropertyPropertyFeatures::className(), ['property_features_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProperties()
    {
        return $this->hasMany(Property::className(), ['id' => 'property_id'])->viaTable('property_property_features', ['property_features_id' => 'id']);
    }
}
