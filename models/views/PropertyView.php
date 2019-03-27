<?php

namespace app\models\views;

use Yii;
use yii\base\Model;
use app\models\tables\Photo;
use app\models\tables\Property;
use app\models\tables\Direction;
use app\models\tables\PropertyFeatures;

class PropertyView extends Model
{
    public $is_archive;

    public $id;
    public $property_name;
    public $property_slug;
    public $property_type;
    public $is_sale;
    public $is_rent;
    public $currency;
    public $sale_price;
    public $rent_price;
    public $address;
    public $map_latitude;
    public $map_longitude;
    public $distance_to_mrar;
    public $with_finishing;
    public $with_furniture;
    public $bathrooms;
    public $bedrooms;
    public $garage;
    public $land_area;
    public $build_area;
    public $description;
    public $photos;
    public $direction;
    public $property_features;

    /**
     * @var Property
     */
    private $_propertyModel;

    /**
     * @var Photo[]
     */
    private $_photoModels;

    /**
     * @var Direction
     */
    private $_directionModel;

    /**
     * @var PropertyFeatures[]
     */
    private $_propertyFeaturesModels;

    public function __construct(Property $propertyModel, array $config = [])
    {
        $this->_propertyModel = $propertyModel;
        $this->_photoModels = $this->_propertyModel->getPhotos()->all();
        $this->_directionModel = $this->_propertyModel->getDirection()->one();
        $this->_propertyFeaturesModels = $this->_propertyModel->getPropertyFeatures()->all();

        $this->loadAttributes();
        parent::__construct($config);
    }

    public static function getPropertyViews(array $property)
    {
        $propertyViews = [];
        foreach ($property as $propertyModel) {
            $propertyViews[] = new PropertyView($propertyModel);
        }
        return $propertyViews;
    }

    public function attributeLabels()
    {
        return array_merge(
            Property::$attributeLabels,
            [
                'property_features' => 'Особенности',
            ]
        );
    }

    public function loadAttributes()
    {
        $this->is_archive = $this->_propertyModel->is_archive;

        $this->id = $this->_propertyModel->id;
        $this->property_name = $this->_propertyModel->property_name;
        $this->property_slug = $this->_propertyModel->property_slug;
        $this->property_type = $this->_propertyModel->property_type;
        $this->is_rent = $this->_propertyModel->is_rent;
        $this->is_sale = $this->_propertyModel->is_sale;
        $this->currency = $this->_propertyModel->currency;
        $this->sale_price = $this->_propertyModel->sale_price;
        $this->rent_price = $this->_propertyModel->rent_price;
        $this->address = $this->_propertyModel->address;
        $this->map_latitude = $this->_propertyModel->map_latitude;
        $this->map_longitude = $this->_propertyModel->map_longitude;
        $this->distance_to_mrar = $this->_propertyModel->distance_to_mrar;
        $this->with_finishing = $this->_propertyModel->with_finishing;
        $this->with_furniture = $this->_propertyModel->with_furniture;
        $this->bathrooms = $this->_propertyModel->bathrooms;
        $this->bedrooms = $this->_propertyModel->bedrooms;
        $this->garage = $this->_propertyModel->garage;
        $this->land_area = $this->_propertyModel->land_area;
        $this->build_area = $this->_propertyModel->build_area;
        $this->description = $this->_propertyModel->description;

        foreach ($this->_photoModels as $photoModel) {
            $this->photos[] = [
                'photoPath' => $photoModel->getPath(),
                'smallPhotoPath' => $photoModel->getPathToSmall(),
            ];
        }

        if (!empty($this->_directionModel)) {
            $this->direction = [
                'name' => $this->_directionModel->name,
                'slug' => $this->_directionModel->slug,
            ];
        }

        foreach ($this->_propertyFeaturesModels as $propertyFeaturesModel) {
            $this->property_features[] = [
                'name' => $propertyFeaturesModel->name,
                'slug' => $propertyFeaturesModel->slug,
            ];
        }
    }
}
