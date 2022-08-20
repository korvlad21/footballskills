<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%players}}`.
 */
class m220820_080300_create_players_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%players}}', [
            'id' => $this->primaryKey(),
            'surname' => $this->string(200)->notNull(),
            'name' => $this->string(200)->notNull(),
            'otchestvo' => $this->string(200)->notNull(),
            'birthday' => $this->date()->notNull(),
            'position' => $this->tinyInteger(),
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
        $this->dropTable('{{%players}}');
    }
}
