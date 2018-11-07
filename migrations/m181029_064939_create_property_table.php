<?php

use yii\db\Migration;

/**
 * Handles the creation of table `property`.
 */
class m181029_064939_create_property_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('property', [
            'id' => $this->primaryKey(),
            'property_name' => $this->string()->notNull(),
            'property_slug' => $this->string()->notNull()->unique(),
            'property_type' => "ENUM('Дом', 'Таунхаус', 'Квартира', 'Участок') NOT NULL",
            'is_sale' => $this->boolean(),
            'is_rent' => $this->boolean(),
            'currency' => "ENUM('₽', '$', '€') NOT NULL",
            'price' => $this->integer()->notNull(),
            'address' => $this->string()->notNull(),
            'map_latitude' => $this->float()->notNull(),
            'map_longitude' => $this->float()->notNull(),
            'direction_id' => $this->integer(),
            'distance_to_mrar' => $this->integer(),//`range`
            'with_finishing' => $this->boolean(),
            'with_furniture' => $this->boolean(),
            'bathrooms' => "ENUM('1', '2', '3', '4', '5', '> 5')",
            'bedrooms' => "ENUM('1', '2', '3', '4', '5', '> 5')",
            'garage' => "ENUM('0', '1', '2', '3', '4', '5', '> 5')",
            'land_area' => $this->float(),
            'build_area' => $this->float(),
            'description' => $this->text(),
            'user_id' => $this->integer()->notNull(),
            'is_archive' => $this->boolean()->defaultValue(true),
            'created_at' => $this->integer(11),
            'updated_at' => $this->integer(11),
        ]);

        $this->createIndex(
            'property_is_archive_idx',
            'property',
            'is_archive'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('property');
    }
}
