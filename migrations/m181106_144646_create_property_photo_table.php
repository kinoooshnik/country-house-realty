<?php

use yii\db\Migration;

/**
 * Handles the creation of table `property_photo`.
 */
class m181106_144646_create_property_photo_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('property_photo', [
            'property_id' => $this->integer()->notNull(),
            'photo_id' => $this->integer()->notNull(),
            'position' => $this->integer()->notNull(),
            'PRIMARY KEY(property_id, photo_id)',
        ]);

        $this->createIndex(
            'property_photo_property_id_idx',
            'property_photo',
            'property_id'
        );

        $this->createIndex(
            'property_photo_photo_id_idx',
            'property_photo',
            'photo_id'
        );

        $this->createIndex(
            'property_photo_photo_id_position_idx',
            'property_photo',
            ['property_id', 'position']
        );

        $this->addForeignKey(
            'property_photo_photo_id_photo_id_fkey',
            'property_photo',
            'photo_id',
            'photo',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'property_photo_property_id_property_id_fkey',
            'property_photo',
            'property_id',
            'property',
            'id',
            'CASCADE'
        );
    }

    public function safeDown()
    {

        $this->dropForeignKey(
            'property_photo_property_id_property_id_fkey',
            'property_photo'
        );

        $this->dropForeignKey(
            'property_photo_photo_id_photo_id_fkey',
            'property_photo'
        );

        $this->dropTable('property_photo');
    }
}
