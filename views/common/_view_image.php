<?php

use yii\helpers\Html;
use yii\helpers\Url;
?>

<div class="row">
    <?php if (!$model->isNewRecord) : ?>
        <?php if ($model) : ?>
            <div class="col-sm-6 img-view">
                <span class="pull-left btn btn-danger delete-image" style="position: absolute;">
                    <?= Html::a(
                        '<span class="glyphicon glyphicon-remove" style=" color: #fff;"></span>',
                        Url::to(['/' . mb_strtolower($image->modelName) . '/image-delete', 'id_img' => $image->id,  'id_model' => $model->id])
                    ) ?>
                </span>
                <a href="<?= $image->getPath() ?>" data-rel="lightcase:g">
                    <?= Html::img($image->getPath('220x220')); ?>
                </a>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</div>