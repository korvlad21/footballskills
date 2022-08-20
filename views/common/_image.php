<?php

use yii\helpers\Html;
use yii\helpers\Url;
?>

<div class="row">
    <?php
    if (!$model->isNewRecord) :
        if ($model) :
    ?>
            <div class="col-sm-4">
                <span class="pull-left btn btn-danger delete-image" style="position: absolute;">
                    <?= Html::a('<span class="glyphicon glyphicon-remove" style=" color: #fff;"></span>', Url::to(['/good/image', 'id' => $model->id, 'name' => $model->name]), ['data-delete-image' => Url::to(['/good/image-delete', 'id' => $model->id, 'name' => $model->name])]) ?>
                </span>
                <?= Html::img($model->image->getPath('200x200')); ?>
            </div>
    <?php
        endif;

    endif; ?>
</div>
<div class="row"></div>