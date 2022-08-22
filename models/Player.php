<?php

namespace app\models;

use Yii;
use yii\db\Expression;
use app\models\AppModel;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

class Player extends AppModel
{

    public $image;

    const NAME_POSITION = [
        1 => 'Вратарь',
        2 => 'Защитник',
        3 => 'Полузащитник',
        4 => 'Нападающий',
    ];
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
                // если вместо метки времени UNIX используется datetime:
                 'value' => new Expression('NOW()'),
            ],
             
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
            [['position', 'is_delete'], 'integer'],
            [['birthday'], 'required'],
            ['birthday', 'date',  'format' => 'php:Y-m-d'],
            [['created_at', 'updated_at'], 'safe'],

        ];
    }

    public function attributeLabels()
    {
        return [
            'surname' => 'Фамилия',
            'name' => 'Имя',
            'otchestvo' => 'Отчество',
            'position' => 'Позиция',
            'birthday' => 'Дата рождения',
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
        return $this->birthday = date('d.m.Y', strtotime($this->birthday));
    }

    public function getPositionName()
    {
        return self::NAME_POSITION[$this->position];
    }

    public static function getPositionStatic($id = null)
    {
        if (is_null($id)) {
            return self::NAME_POSITION[count(self::NAME_POSITION)];
        }

        if (!isset($constatnt[$id])) {
            return self::NAME_POSITION[count(self::NAME_POSITION)];
        }

        return $constatnt[$id];
    }

    public function getPosition($id = null)
    {
        return self::getPositionStatic(is_null($id) ? $this->position : $id);
    }

    public function setCharacteristics($arrayPostCharacts)
    {
        if (!empty($arrayPostCharacts)) {

            $oldArray = [];
            $oldCharacts = PlayerCharacteristic::find()->where(['good_code' => $this->vendor_code])->all();
            foreach ($oldCharacts as $oldCharact) {
                $oldArray[$oldCharact->char_id] = $oldCharact->value;
            }

            $resultCompare = array_diff_assoc($arrayPostCharacts, $oldArray);

            if (!empty($resultCompare)) {
                GoodCharacteristic::deleteAll(['good_code' => $this->vendor_code]);

                foreach ($arrayPostCharacts as $key => $values) {
                    $newGoodCharact = new GoodCharacteristic();
                    $newGoodCharact->good_code = $this->vendor_code;
                    $newGoodCharact->char_id = (int)$key;
                    $newGoodCharact->value = trim($values['value']);
                    $newGoodCharact->units_id = !empty($values['units']) ? (int)$values['units'] : NULL;
                    $newGoodCharact->save();
                }
            }
        }
    }

}
