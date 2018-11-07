<?php

use yii\db\Migration;

class m181104_103629_create_direction_table extends Migration
{

    public function safeUp()
    {
        $this->createTable('direction', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull()->unique(),
            'slug' => $this->string()->notNull()->unique(),
        ]);

        $this->createIndex(
            'direction_slug_idx',
            'direction',
            'slug'
        );

        $this->addForeignKey(
            'property_direction_id_direction_id_fkey',
            'property',
            'direction_id',
            'direction',
            'id',
            'CASCADE'
        );
    }

    public function safeDown()
    {
        $this->dropForeignKey(
            'property_direction_id_direction_id_fkey',
            'property'
        );

        $this->dropTable('direction');
    }
}
