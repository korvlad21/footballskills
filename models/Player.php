<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

class Player extends ActiveRecord
{

    public $image;
    public function behaviors()
    {
        return [
            TimestampBehavior::class,
        ];
    }

    public static function tableName()
    {
        return 'players';
    }

    public function rules()
    {
        return [
            [['surname', 'name', 'otchestvo'], 'required'],
            [['surname'], 'string', 'max' => 200],
            [['name'], 'string', 'max' => 200],
            [['otchestvo'], 'string', 'max' => 200],
            [['position', 'created_at', 'updated_at','is_delete'], 'integer'],
            [['birthday'], 'required'],
            ['birthday', 'datetime', 'timestampAttribute' => 'date', 'format' => 'php:d.m.Y'],

        ];
    }

    public function attributeLabels()
    {
        return [
            'surname' => 'Название',
            'name' => 'Название',
            'otchestvo' => 'Краткое описание',
            'position' => 'Контент',
            'birthday' => 'Превью',
            'is_delete' => 'Отметка удаления',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата обновления',
        ];
    }

    public static function findWhere()
    {
        $query = Player::find()->where(['is_delete' => 0]);

        return $query;
    }


    public function getBirthday()
    {
        return $this->birthday = date('d.m.Y', $this->birthday);
    }

}
