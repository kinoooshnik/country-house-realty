<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\tables\Property;
use app\models\views\PropertyView;
use yii\helpers\Json;

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
    public $distance_to_mrar_from;
    public $distance_to_mrar_to;
    public $with_finishing;
    public $with_furniture;
    public $bathrooms;
    public $bedrooms;
    public $garage;
    public $build_area_from;
    public $build_area_to;
    public $land_area_from;
    public $land_area_to;
    public $direction_id;
    public $property_features;

    public function rules()
    {
        return [
            [['property_type', 'currency', 'bathrooms', 'bedrooms', 'garage', 'description', 'price_from', 'price_to'], 'string'],
            [['distance_to_mrar_from', 'distance_to_mrar_to'], 'integer'],
            [['land_area_from', 'land_area_to', 'build_area_from', 'build_area_to',], 'number'],
            ['property_type', 'in', 'range' => ['Дом', 'Таунхаус', 'Квартира', 'Участок'], 'allowArray' => true],
            ['ad_type', 'in', 'range' => ['Аренда', 'Продажа'], 'allowArray' => true],
            ['currency', 'in', 'range' => ['₽', '$', '€'], 'allowArray' => true],
            ['with_finishing', 'in', 'range' => ['C отделкой', 'Без отделки'], 'allowArray' => true],
            ['with_furniture', 'in', 'range' => ['C мебелью', 'Без мебели'], 'allowArray' => true],
            ['direction_id', 'each', 'rule' => ['integer']],
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
        if (isset($params['PropertyListSearch'])) {
            $this->load($params);
            if (!empty($this->property_type)) {
                $query->andWhere(['in', 'property_type', $this->property_type]);
            }
            if (!empty($this->ad_type)) {
                if (array_search('Продажа', $this->ad_type) !== false) {
                    $query->andWhere(['is_sale' => true]);
                }
                if (array_search('Аренда', $this->ad_type) !== false) {
                    $query->andWhere(['is_rent' => true]);
                }
            }
            if (!empty($this->price_from) || !empty($this->price_to)) {
                if (empty($this->currency)) {
                    if (!empty($this->price_from) && !empty($this->price_to)) {
                        $priceFrom = intval(preg_replace('/\s+/u', '', $this->price_from));
                        $priceTo = intval(preg_replace('/\s+/u', '', $this->price_to));
                        $query->andWhere([
                            'or',
                            ['between', 'sale_price', $priceFrom, $priceTo],
                            ['between', 'rent_price', $priceFrom, $priceTo]
                        ]);
                    } elseif (!empty($this->price_from)) {
                        $priceFrom = intval(preg_replace('/\s+/u', '', $this->price_from));
                        $query->andWhere([
                            'or',
                            ['>', 'sale_price', $priceFrom],
                            ['>', 'rent_price', $priceFrom]
                        ]);
                        $query->andWhere(['>', 'sale_price', intval(preg_replace('/\s+/u', '', $this->price_from))]);
                    } elseif (!empty($this->price_to)) {
                        $priceTo = intval(preg_replace('/\s+/u', '', $this->price_to));
                        $query->andWhere([
                            'or',
                            ['<', 'sale_price', $priceTo],
                            ['<', 'rent_price', $priceTo]
                        ]);
                    }
                } else {
                    $rubUsd = 66.19;
                    $rubEur = 75.03;

                    switch ($this->currency) {
                        case '₽':
                            $secondCurrency = '$';
                            $secondFactor = 1 / $rubUsd;
                            $thirdCurrency = '€';
                            $thirdFactor = 1 / $rubEur;
                            break;
                        case '$':
                            $secondCurrency = '₽';
                            $secondFactor = $rubUsd;
                            $thirdCurrency = '€';
                            $thirdFactor = $rubUsd / $rubEur;
                            break;
                        case '€':
                            $secondCurrency = '₽';
                            $secondFactor = $rubEur;
                            $thirdCurrency = '$';
                            $thirdFactor = $rubEur / $rubUsd;
                            break;
                    }
                    if (!empty($this->price_from)) {
                        $priceFrom = intval(preg_replace('/\s+/u', '', $this->price_from));
                        $secondPriceFrom = intval($priceFrom * $secondFactor);
                        $thirdPriceFrom = intval($priceFrom * $thirdFactor);
                    }
                    if (!empty($this->price_to)) {
                        $priceTo = intval(preg_replace('/\s+/u', '', $this->price_to));
                        $secondPriceTo = intval($priceTo * $secondFactor);
                        $thirdPriceTo = intval($priceTo * $thirdFactor);
                    }

                    if (!empty($this->price_from) && !empty($this->price_to)) {
                        $query->andWhere([
                            'or',
                            [
                                'and',
                                ['=', 'currency', $this->currency],
                                ['or', ['between', 'sale_price', $priceFrom, $priceTo], ['between', 'rent_price', $priceFrom, $priceTo]]
                            ],
                            [
                                'or',
                                [
                                    'and',
                                    ['=', 'currency', $secondCurrency],
                                    ['or', ['between', 'sale_price', $secondPriceFrom, $secondPriceTo], ['between', 'rent_price', $secondPriceFrom, $secondPriceTo]]
                                ],
                                [
                                    'and',
                                    ['=', 'currency', $thirdCurrency],
                                    ['or', ['between', 'sale_price', $thirdPriceFrom, $thirdPriceTo], ['between', 'rent_price', $thirdPriceFrom, $thirdPriceTo]]
                                ]
                            ]
                        ]);
                    } elseif (!empty($this->price_from)) {
                        $query->andWhere([
                            'or',
                            [
                                'and',
                                ['=', 'currency', $this->currency],
                                ['or', ['>', 'sale_price', $priceFrom], ['>', 'rent_price', $priceFrom]]
                            ],
                            [
                                'or',
                                [
                                    'and',
                                    ['=', 'currency', $secondCurrency],
                                    ['or', ['>', 'sale_price', $secondPriceFrom], ['>', 'rent_price', $secondPriceFrom]]
                                ],
                                [
                                    'and',
                                    ['=', 'currency', $thirdCurrency],
                                    ['or', ['>', 'sale_price', $thirdPriceFrom], ['>', 'rent_price', $thirdPriceFrom]]
                                ]
                            ]
                        ]);
                    } elseif (!empty($this->price_to)) {
                        $query->andWhere([
                            'or',
                            [
                                'and',
                                ['=', 'currency', $this->currency],
                                ['or', ['<', 'sale_price', $priceTo], ['<', 'rent_price', $priceTo]]
                            ],
                            [
                                'or',
                                [
                                    'and',
                                    ['=', 'currency', $secondCurrency],
                                    ['or', ['<', 'sale_price', $secondPriceTo], ['<', 'rent_price', $secondPriceTo]]
                                ],
                                [
                                    'and',
                                    ['=', 'currency', $thirdCurrency],
                                    ['or', ['<', 'sale_price', $thirdPriceTo], ['<', 'rent_price', $thirdPriceTo]]
                                ]
                            ]
                        ]);
                    }
                }
            }

            if (!empty($this->distance_to_mrar_from) && !empty($this->distance_to_mrar_to)) {
                $query->andWhere([
                    'and',
                    ['not', ['distance_to_mrar' => null]],
                    ['between', 'distance_to_mrar', $this->distance_to_mrar_from, $this->distance_to_mrar_to]
                ]);
            } elseif (!empty($this->distance_to_mrar_from)) {
                $query->andWhere([
                    'and',
                    ['not', ['distance_to_mrar' => null]],
                    ['>=', 'distance_to_mrar', $this->distance_to_mrar_from]
                ]);
            } elseif (!empty($this->distance_to_mrar_to)) {
                $query->andWhere([
                    'and',
                    ['not', ['distance_to_mrar' => null]],
                    ['<=', 'distance_to_mrar', $this->distance_to_mrar_to]
                ]);
            }

            if (!empty($this->with_furniture)) {
                if (array_search('C мебелью', $this->with_furniture) !== false) {
                    $query->andWhere(['with_furniture' => true]);
                } elseif (array_search('Без мебели', $this->with_furniture) !== false) {
                    $query->andWhere(['with_furniture' => false]);
                }
            }

            if (!empty($this->with_finishing)) {
                if (array_search('C отделкой', $this->with_finishing) !== false) {
                    $query->andWhere(['with_finishing' => true]);
                } elseif (array_search('Без отделки', $this->with_finishing) !== false) {
                    $query->andWhere(['with_finishing' => false]);
                }
            }

            if (!empty($this->bedrooms)) {
                $query->andWhere(['in', 'bedrooms', $this->bedrooms]);
            }

            if (!empty($this->garage)) {
                $query->andWhere(['in', 'garage', $this->garage]);
            }

            if (!empty($this->bathrooms)) {
                $query->andWhere(['in', 'bathrooms', $this->bathrooms]);
            }

            if (!empty($this->build_area_from) && !empty($this->build_area_to)) {
                $query->andWhere([
                    'and',
                    ['not', ['build_area' => null]],
                    ['between', 'build_area', $this->build_area_from, $this->build_area_to]
                ]);
            } elseif (!empty($this->build_area_from)) {
                $query->andWhere([
                    'and',
                    ['not', ['build_area' => null]],
                    ['>=', 'build_area', $this->build_area_from]
                ]);
            } elseif (!empty($this->build_area_to)) {
                $query->andWhere([
                    'and',
                    ['not', ['build_area' => null]],
                    ['<=', 'build_area', $this->build_area_to]
                ]);
            }

            if (!empty($this->land_area_from) && !empty($this->land_area_to)) {
                $query->andWhere([
                    'and',
                    ['not', ['land_area' => null]],
                    ['between', 'land_area', $this->land_area_from, $this->land_area_to]
                ]);
            } elseif (!empty($this->land_area_from)) {
                $query->andWhere([
                    'and',
                    ['not', ['land_area' => null]],
                    ['>=', 'land_area', $this->land_area_from]
                ]);
            } elseif (!empty($this->land_area_to)) {
                $query->andWhere([
                    'and',
                    ['not', ['land_area' => null]],
                    ['<=', 'land_area', $this->land_area_to]
                ]);
            }

            if (!empty($this->direction_id)) {
                $query->andWhere(['in', 'direction_id', $this->direction_id]);
            }
        }

        return $query;
    }
}
