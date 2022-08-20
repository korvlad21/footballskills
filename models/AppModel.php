<?php

namespace app\models;


use \yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;


abstract class AppModel extends ActiveRecord
{
   
    public static function getModelById($id)
    {
        $model = self::find()->where(['id' => $id, 'is_delete' => 0])->one();
        if ($model !== null) {
            return $model;
        }
        throw new NotFoundHttpException('Запрашиваемая страница не существует');
    }

    public static function getListForSelect($attributeName = null)
    {
        $values = [];
        if (!is_null($attributeName)) {
            $values =  ArrayHelper::map(self::find()->all(), 'id', $attributeName);
        }

        return $values;
    }



    public static function dropDown()
    {
        return ArrayHelper::map(self::find()->all(), 'id', 'name');
    }

    public static function getListYesNo($id = false)
    {
        $array = [
            'Нет',
            'Да',
        ];
        if (is_bool($id)) {
            return $array;
        }
        return $array[$id];
    }
}
