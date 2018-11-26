<?php

namespace app\models\tables;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\SluggableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\Query;

/**
 * This is the model class for table "property".
 *
 * @property int $id
 * @property string $property_name
 * @property string $property_slug
 * @property string $property_type
 * @property boolean $is_sale
 * @property boolean $is_rent
 * @property string $currency
 * @property int $sale_price
 * @property int $rent_price
 * @property string $address
 * @property double $map_latitude
 * @property double $map_longitude
 * @property int $direction_id
 * @property int $distance_to_mrar
 * @property int $with_finishing
 * @property int $with_furniture
 * @property string $bathrooms
 * @property string $bedrooms
 * @property string $garage
 * @property int $land_area
 * @property int $build_area
 * @property string $description
 * @property int $user_id
 * @property boolean $is_archive
 * @property int $created_at
 * @property int $updated_at
 *
 * @property Direction $direction
 * @property PropertyPhoto[] $propertyPhotos
 * @property Photo[] $photos
 * @property PropertyPropertyFeatures[] $propertyPropertyFeatures
 * @property PropertyFeatures[] $propertyFeatures
 */
class Property extends ActiveRecord
{
    public static $attributeLabels = [
        'id' => 'ID',
        'user_id' => 'User ID',
        'property_name' => 'Название',
        'property_slug' => 'Property Slug',
        'property_type' => 'Тип недвижимости',
        'is_sale' => 'Продажа',
        'is_rent' => 'Аренда',
        'direction_id' => 'Направление',
        'distance_to_mrar' => 'Расстояние до МКАД км.',
        'with_finishing' => 'Тип отделки',
        'with_furniture' => 'Наличее мебели',
        'currency' => 'Валюта',
        'sale_price' => 'Цена продажи',
        'rent_price' => 'Цена аренды',
        'address' => 'Адрес',
        'map_latitude' => 'Широта',
        'map_longitude' => 'Долгота',
        'bathrooms' => 'Санузлов',
        'bedrooms' => 'Спален',
        'garage' => 'Гаражей',
        'land_area' => 'Площадь участка сот.',
        'build_area' => 'Жилая площадь м²',
        'description' => 'Описание',
        'is_archive' => 'В архиве',
        'created_at' => 'Создан',
        'updated_at' => 'Обновлен',
    ];

    public function behaviors()
    {
        return [
            [
                'class' => SluggableBehavior::className(),
                'attribute' => 'property_name',
                'slugAttribute' => 'property_slug',
                'ensureUnique' => true,
            ],
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
        return 'property';
    }

    public function rules()
    {
        return [
            [['user_id', 'property_name', 'property_type', 'currency', 'address'], 'required'],
            [['user_id', 'direction_id', 'distance_to_mrar', 'sale_price', 'rent_price'], 'integer'],
            [['address', 'property_type', 'currency', 'bathrooms', 'bedrooms', 'garage', 'description'], 'string'],
            [['property_type', 'currency', 'bathrooms', 'bedrooms', 'garage', 'description'], 'default', 'value' => null],
            [['map_latitude', 'map_longitude', 'land_area', 'build_area'], 'number'],
            [['property_name'], 'string', 'max' => 255],
            [['is_sale', 'is_rent', 'is_archive', 'with_finishing', 'with_furniture'], 'boolean'],
            ['property_type', 'in', 'range' => ['Дом', 'Таунхаус', 'Квартира', 'Участок']],
            ['currency', 'in', 'range' => ['₽', '$', '€']],
            ['bathrooms', 'in', 'range' => ['1', '2', '3', '4', '5', '> 5']],
            ['bedrooms', 'in', 'range' => ['1', '2', '3', '4', '5', '> 5']],
            ['garage', 'in', 'range' => ['0', '1', '2', '3', '4', '5', '> 5']],
            [['direction_id'], 'exist', 'skipOnError' => true, 'targetClass' => Direction::className(), 'targetAttribute' => ['direction_id' => 'id']],
        ];
    }

    public function save($runValidation = true, $attributeNames = null)
    {
        //return $this->validate();
        return parent::save($runValidation, $attributeNames);
    }

    public function attributeLabels()
    {
        return self::$attributeLabels;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDirection()
    {
        return $this->hasOne(Direction::className(), ['id' => 'direction_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPropertyPhotos()
    {
        return $this->hasMany(PropertyPhoto::className(), ['property_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPhotos()
    {
        return Photo::find()->leftJoin(PropertyPhoto::tableName(), 'photo_id=id')->where(['property_id' => $this->id])->orderBy('position');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMainPhoto()
    {
        return Photo::find()->leftJoin(PropertyPhoto::tableName(), 'photo_id=id')->where(['property_id' => $this->id])->orderBy('position')->limit(1);
    }

    /**
     * @return string
     */
    public function getMainPhotoURL()
    {
        $photoModel = $this->getMainPhoto()->one();
        return empty($photoModel) ? null : '/uploads/property/original/' . $photoModel->name;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPropertyPropertyFeatures()
    {
        return $this->hasMany(PropertyPropertyFeatures::className(), ['property_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPropertyFeatures()
    {
        return $this->hasMany(PropertyFeatures::className(), ['id' => 'property_features_id'])->viaTable('property_property_features', ['property_id' => 'id']);
    }
}
