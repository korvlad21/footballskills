<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\db\Expression;
use app\models\AppModel;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

class Characteristic extends AppModel
{
    public static function tableName()
    {
        return 'characteristics';
    }

    public function behaviors(): array
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

    public function rules()
    {
        return [
            [['name'], 'required'],
            ['name', 'unique'],
            [['name'], 'string', 'max' => 200],
            [['description'], 'string', 'max' => 255],
            [['parent_id'], 'integer'],
            [['is_child'], 'integer'],
            [['is_delete'], 'integer'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Наименование',
            'description' => 'Описание категории',
            'parent_id' => 'Родительская категория',
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

    public static function getListForSelectChild($attributeName = null)
    {
        $values = [];
        if (!is_null($attributeName)) {
            $values =  ArrayHelper::map(self::find()->where(['is_child'=>1])->all(), 'id', $attributeName);
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
