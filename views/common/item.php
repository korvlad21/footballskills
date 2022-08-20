<?php
use yii\helpers\Url;

$str = "<div data-upgrade-form='".$model->id."'><a href='".Url::to(['update-grid', 'id' => $model->id])."' data-dropdown='".$model->id."'>".$model->getDropDownList(null, 'email')."</div>";
echo $str;