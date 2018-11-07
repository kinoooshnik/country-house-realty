<?php

use yii\db\Migration;

class m181105_104408_create_property_features_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('property_features', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull()->unique(),
            'slug' => $this->string()->notNull()->unique(),
        ]);

        $this->createIndex(
            'property_features_name_idx',
            'property_features',
            'name'
        );

        $this->createIndex(
            'property_features_slug_idx',
            'property_features',
            'slug'
        );

        $this->createTable('property_property_features', [
            'property_id' => $this->integer()->notNull(),
            'property_features_id' => $this->integer()->notNull(),
            'PRIMARY KEY(property_id, property_features_id)',
        ]);

        $this->createIndex(
            'property_property_features_property_id_idx',
            'property_property_features',
            'property_id'
        );

        $this->createIndex(
            'property_property_features_property_features_id_idx',
            'property_property_features',
            'property_features_id'
        );

        $this->addForeignKey(
            'property_property_features_p_f_id_p_f_id_fkey',
            'property_property_features',
            'property_features_id',
            'property_features',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'property_property_features_property_id_property_id_fkey',
            'property_property_features',
            'property_id',
            'property',
            'id',
            'CASCADE'
        );
    }

    public function safeDown()
    {

        $this->dropForeignKey(
            'property_property_features_property_id_property_id_fkey',
            'property_property_features'
        );

        $this->dropForeignKey(
            'property_property_features_p_f_id_p_f_id_fkey',
            'property_property_features'
        );

        $this->dropTable('property_property_features');

        $this->dropTable('property_features');
    }
}
