<?php

namespace app\models;

use Yii;
use yii\helpers\Url;
use yii\db\Expression;
use app\models\AppModel;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

class Category extends AppModel
{
    public static function tableName()
    {
        return 'categories';
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
            [['name'], 'string', 'max' => 200],
            [['description'], 'string'],
            [['parent_id'], 'integer'],
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
        return $this->hasOne(Category::class, ['id' => 'parent_id']);
    }

    public function getChild()
    {
        return $this->hasMany(Category::class, ['parent_id' => 'id']);
    }



    public static function getListForSelectInCategory($this_id)
    {
        $values = [];
        $values[-1] = 'Нет';

        $query = self::find();
        if (!empty($this_id)) {
            $query->where(['!=', 'id', $this_id]);
        }
        $cats = $query->all();

        foreach ($cats as $cat) {
            $id = $cat->id;

            $name = $cat->name;
            $parent = $cat->parent;
            if (!empty($parent)) {
                $name .= " - (" . $parent->name . ")";
            }

            $values[$id] = $name;
        }

        return $values;
    }


    public static function getArrayTree($categories, $parentId = "0")
    {
        $catsArr = [];
        foreach ($categories as $category) {
            if ($category->is_delete == 0) {
                $catsArr[$category->id] = [];
                $catsArr[$category->id]['id']          = $category->id;
                $catsArr[$category->id]['name']        = $category->name;
                $catsArr[$category->id]['parent']      = (!empty($category->parent_id) && ($category->parent_id !== 0)) ? $category->parent_id : "0";
            }
        }

        $treeCategories = self::buildTree($catsArr, $parentId);

        return $treeCategories;
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

    //$parent_id - какой parentid считать корневым
    //по умолчанию 0 (корень)
    public static function build_tree($cats, $parent_id, $id)
    {
        if (is_array($cats) && isset($cats[$parent_id])) {
            $tree = '<ul>';
            foreach ($cats[$parent_id] as $cat) {
                $tree .= '<li>';
                $tree .= ($cat['id'] == $id) ? "<b>" . $cat['name'] . "</b>" : '<a href="' . Url::to(['update', 'id' => $cat['id'] ]) . '">' . $cat['name'] . '</a>';
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
        $catChilds=$this->child;
        foreach($catChilds as $catChild)
        {
            $catChild->parent_id=$this->parent_id;
            $catChild->save();
        }
        
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
