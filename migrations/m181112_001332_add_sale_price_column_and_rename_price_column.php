<?php

use yii\db\Migration;
use app\models\tables\Property;

/**
 * Class m181112_001332_add_sale_price_column_and_rename_price_column
 */
class m181112_001332_add_sale_price_column_and_rename_price_column extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
		$this->addColumn('property', 'sale_price', $this->integer());
		$this->addColumn('property', 'rent_price', $this->integer());
        $properties = Property::find()->all();
        foreach ($properties as $property) {
            if ($property->is_rent) {
				$property->rent_price = $property->price;
            }
            if ($property->is_sale) {
				$property->sale_price = $property->price;
			}
			$property->save();
		}
		$this->dropColumn('property', 'price');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
		$this->addColumn('property', 'price', $this->integer());
        $properties = Property::find()->all();
        foreach ($properties as $property) {
            if (!empty($property->rent_price)) {
				$property->price = $property->rent_price;
            }
            if (!empty($property->sale_price)) {
				$property->price = $property->sale_price;
			}
			$property->save();
		}
		$this->dropColumn('property', 'sale_price');
		$this->dropColumn('property', 'rent_price');
    }
}
