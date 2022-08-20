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
            'is_child' => $this->boolean()->notNull()->defaultValue(true),
            'is_delete' => $this->boolean()->notNull()->defaultValue(false),
            'created_at' => $this->timestamp(),
            'updated_at' => $this->timestamp(),
        ]);
        $dates=[
            [
                'name' => 'Физические качества',
                'is_child' => 0,
            ],
            [
                'name' => 'Природные данные',
                'parent_id' => 1,
            ],
            [
                'name' => 'Активность',
                'parent_id' => 1,
            ],
            [
                'name' => 'Вратарская техника',
                'is_child' => 0,
            ],
            [
                'name' => 'Базовая техника',
                'is_child' => 0,
                'parent_id' => 4
            ],
            [
                'name' => 'ТПМ на месте (верх, середина, низ)',
                'parent_id' => 5
            ],
        ];
        foreach($dates as $data)
        {
            $this->insert('characteristics', $data);
        }
       
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%characteristics}}');
    }
}
