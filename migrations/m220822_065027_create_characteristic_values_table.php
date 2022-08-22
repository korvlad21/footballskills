<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%characteristic_values}}`.
 */
class m220822_065027_create_characteristic_values_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%characteristic_values}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'characteristic_id' => $this->integer()->notNull(),
            'value' => $this->tinyInteger(),
        ]);

        $this->createIndex(
            'idx-characteristic_values-user_id',
            'characteristic_values',
            'user_id'
        );

        $this->addForeignKey(
            'fk-characteristic_values-user_id',
            'characteristic_values',
            'user_id',
            'users',
            'id',
            'CASCADE'
        );

        $this->createIndex(
            'idx-characteristic_values-characteristic_id',
            'characteristic_values',
            'characteristic_id'
        );

        $this->addForeignKey(
            'fk-characteristic_values-characteristic_id',
            'characteristic_values',
            'characteristic_id',
            'characteristics',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey(
            'fk-characteristic_values-user_id',
            'characteristic_values'
        );

        // drops index for column `author_id`
        $this->dropIndex(
            'idx-characteristic_values-user_id',
            'characteristic_values'
        );

        // drops foreign key for table `category`
        $this->dropForeignKey(
            'fk-characteristic_values-characteristic_id',
            'characteristic_values'
        );

        // drops index for column `category_id`
        $this->dropIndex(
            'idx-characteristic_values-characteristic_id',
            'characteristic_values'
        );

        $this->dropTable('{{%characteristic_values}}');
    }
}
