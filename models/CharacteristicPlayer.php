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

    public function getParent()
    {
        return $this->hasOne(Characteristic::class, ['id' => 'parent_id']);
    }

    public function getChild()
    {
        return $this->hasMany(Characteristic::class, ['parent_id' => 'id']);
    }



    public static function getListForSelectInCharacteristic($model)
    {

        $values = [];
        $values[-1] = 'Нет';

        $query = self::find();
        if (!empty($model->id)) {
            $query->where(['!=', 'id', $model->id]);
        }
        $cats = $query->all();
        foreach ($cats as $cat) {
            $id = $cat->id;

            $name = $cat->name;
            $values[$id] = $name;
        }

        return $values;
    }



    public static function form_tree($mess)
    {
        if (!is_array($mess)) {
            return false;
        }
        $tree = array();
        foreach ($mess as $value) {
            $tree[$value['parent_id']][] = $value;
        }
        return $tree;
    }

    public static function build_tree($cats, $parent_id, $id)
    {
        if (is_array($cats) && isset($cats[$parent_id])) {
            $tree = '<ul>';
            foreach ($cats[$parent_id] as $cat) {
                $tree .= '<li>';
                $tree .= ($cat['id'] == $id) ? "<b>" . $cat['name'] . "</b>" : '<a href="' . Url::to(['update', 'id' => $cat['id']]) . '">' . $cat['name'] . '</a>';
                $tree .= self::build_tree($cats, $cat['id'], $id);
                $tree .= '</li>';
            }
            $tree .= '</ul>';
        } else {
            return false;
        }
        return $tree;
    }
    public function changeChildParentId()
    {
        $catChilds = $this->child;
        $this->isChild($catChilds);
        foreach ($catChilds as $catChild) {
            $catChild->parent_id = $this->parent_id;
            $catChild->save();
        }
    }

    public function isChild($catChilds=null)
    {
        if(!$catChilds)
        {
            $catChilds = $this->child;
        }
        if (empty($catChilds)) {
            $this->is_child = 1;
        } else {
            $this->is_child = 0;
        }
        $this->save();
    }


    public function prepareParent()
    {
        $parent = $this->parent_id;

        if (empty($this->parent_id) || ($this->parent_id == -1)) {
            $parent = NULL;
        }

        return $parent;
    }
}
