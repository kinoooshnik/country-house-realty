<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\tables\Property;
use app\models\views\PropertyView;

/**
 * PropertySearch represents the model behind the search form of `app\models\Property`.
 */
class PropertyListSearch extends Model
{
    public $property_type;
    public $ad_type;
    public $currency;
    public $price_from;
    public $price_to;
    public $distance_to_mrar;
    public $with_finishing;
    public $with_furniture;
    public $bathrooms;
    public $bedrooms;
    public $garage;
    public $land_area;
    public $build_area;
    public $direction;
    public $property_features;

    public function rules()
    {
        return [
            [['property_type', 'currency', 'bathrooms', 'bedrooms', 'garage', 'description'], 'string'],
            [['price_from', 'price_to', 'distance_to_mrar'], 'integer'],
            [['land_area', 'build_area'], 'number'],
            ['property_type', 'in', 'range' => ['Дом', 'Таунхаус', 'Квартира', 'Участок'], 'allowArray' => true],
            ['ad_type', 'in', 'range' => ['Аренда', 'Продажа'], 'allowArray' => true],
            ['currency', 'in', 'range' => ['₽', '$', '€'], 'allowArray' => true],
            ['with_finishing', 'in', 'range' => ['C отделкой', 'Без отделки'], 'allowArray' => true],
            ['with_furniture', 'in', 'range' => ['C мебелью', 'Без мебели'], 'allowArray' => true],
            ['bathrooms', 'in', 'range' => ['🛇', '1', '2', '3', '4', '5', '> 5']],
            ['bedrooms', 'in', 'range' => ['🛇', '1', '2', '3', '4', '5', '> 5']],
            ['garage', 'in', 'range' => ['🛇', '0', '1', '2', '3', '4', '5', '> 5']],
            ['property_features', 'each', 'rule' => ['string']],
        ];
    }

    public function attributeLabels()
    {
        return array_merge(
            Property::$attributeLabels,
            [
                'ad_type' => 'Тип объявления',
                'property_features' => 'Особенности',
            ]
        );
    }
    
    /**
     * @param array $params
     *
     * @return \yii\db\ActiveQuery
     */
    public function search($params)
    {
        $query = Property::find();

        return $query;
    }
}
