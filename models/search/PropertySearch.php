<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\tables\Property;

/**
 * PropertySearch represents the model behind the search form of `app\models\Property`.
 */
class PropertySearch extends Property
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'is_sale', 'is_rent', 'sale_price', 'rent_price', 'direction_id', 'distance_to_mrar', 'with_finishing', 'with_furniture', 'user_id', 'is_archive', 'created_at', 'updated_at'], 'integer'],
            [['property_name', 'property_slug', 'property_type', 'currency', 'address', 'bathrooms', 'bedrooms', 'garage', 'description'], 'safe'],
            [['map_latitude', 'map_longitude', 'land_area', 'build_area'], 'number'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Property::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'is_sale' => $this->is_sale,
            'is_rent' => $this->is_rent,
            'sale_price' => $this->sale_price,
            'rent_price' => $this->rent_price,
            'map_latitude' => $this->map_latitude,
            'map_longitude' => $this->map_longitude,
            'direction_id' => $this->direction_id,
            'distance_to_mrar' => $this->distance_to_mrar,
            'with_finishing' => $this->with_finishing,
            'with_furniture' => $this->with_furniture,
            'land_area' => $this->land_area,
            'build_area' => $this->build_area,
            'user_id' => $this->user_id,
            'is_archive' => $this->is_archive,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'property_name', $this->property_name])
            ->andFilterWhere(['like', 'property_slug', $this->property_slug])
            ->andFilterWhere(['like', 'property_type', $this->property_type])
            ->andFilterWhere(['like', 'currency', $this->currency])
            ->andFilterWhere(['like', 'address', $this->address])
            ->andFilterWhere(['like', 'bathrooms', $this->bathrooms])
            ->andFilterWhere(['like', 'bedrooms', $this->bedrooms])
            ->andFilterWhere(['like', 'garage', $this->garage])
            ->andFilterWhere(['like', 'description', $this->description]);

        return $dataProvider;
    }
}
