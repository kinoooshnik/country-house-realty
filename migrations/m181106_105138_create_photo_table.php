<?php

use yii\db\Migration;

/**
 * Handles the creation of table `photo`.
 */
class m181106_105138_create_photo_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('photo', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->unique()->notNull(),
            'uploaded_at' => $this->integer(11),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('photo');
    }
}
