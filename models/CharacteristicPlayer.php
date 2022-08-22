<?php

namespace app\models;

use Yii;
use yii\helpers\Url;
use yii\db\Expression;
use app\models\AppModel;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

class CharacteristicPlayer extends AppModel
{
    public static function tableName()
    {
        return 'characteristic_players';
    }

    public function behaviors(): array
    {
        return [

        ];
    }

    public function rules()
    {
        return [
            [['value'], 'required'],
            [['player_id'], 'integer'],
            [['characteristic_id'], 'integer'],
            [['value'], 'integer', 'min' => 1, 'max' => 5],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'player_id' => 'Идентификатор игрока',
            'characteristic_id' => 'Идентификатор характеристики',
            'value' => 'Показатель (от 1 до 5)',
        ];
    }

    public function getCharacteristic()
    {
        return $this->hasOne(Characteristic::class, ['id' => 'characteristic_id']);
    }

    public function getPlayer()
    {
        return $this->hasOne(Player::class, ['id' => 'player_id']);
    }
}
