<?php

use yii\db\Migration;

/**
 * Handles the creation of table `user`.
 */
class m181015_114236_create_user_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('user', [
            'id' => $this->primaryKey(),
            'email' => $this->string()->notNull()->unique(),
            'first_name' => $this->string(),
            'second_name' => $this->string(),
            'auth_key' => $this->string(),
            'password_hash' => $this->string()->notNull(),
            'created_at' => $this->integer(11),
            'updated_at' => $this->integer(11),
        ]);
    }

    public function safeDown()
    {
        $this->dropTable('user');
    }
}
