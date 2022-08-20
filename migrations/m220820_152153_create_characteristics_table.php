<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%characteristics}}`.
 */
class m220820_152153_create_characteristics_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%characteristics}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(200)->notNull(),
            'description' => $this->string(),
            'parent_id' => $this->integer(),
            'is_child' => $this->boolean()->notNull()->defaultValue(false),
            'is_delete' => $this->boolean()->notNull()->defaultValue(false),
            'created_at' => $this->timestamp(),
            'updated_at' => $this->timestamp(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%characteristics}}');
    }
}
