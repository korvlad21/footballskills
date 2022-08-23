<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%characteristic_players}}`.
 */
class m220822_102739_create_characteristic_players_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%characteristic_players}}', [
            'id' => $this->primaryKey(),
            'player_id' => $this->integer()->notNull(),
            'characteristic_id' => $this->integer()->notNull(),
            'value' => $this->tinyInteger(),
        ]);

        $this->createIndex(
            'idx-characteristic_players-player_id',
            'characteristic_players',
            'player_id'
        );

        $this->addForeignKey(
            'fk-characteristic_players-player_id',
            'characteristic_players',
            'player_id',
            'players',
            'id',
            'CASCADE'
        );

        $this->createIndex(
            'idx-characteristic_players-characteristic_id',
            'characteristic_players',
            'characteristic_id'
        );

        $this->addForeignKey(
            'fk-characteristic_players-characteristic_id',
            'characteristic_players',
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
            'fk-characteristic_players-player_id',
            'characteristic_players'
        );

        // drops index for column `author_id`
        $this->dropIndex(
            'idx-characteristic_players-player_id',
            'characteristic_players'
        );

        // drops foreign key for table `category`
        $this->dropForeignKey(
            'fk-characteristic_players-characteristic_id',
            'characteristic_players'
        );

        // drops index for column `category_id`
        $this->dropIndex(
            'idx-characteristic_players-characteristic_id',
            'characteristic_players'
        );

        $this->dropTable('{{%characteristic_players}}');
    }
}
