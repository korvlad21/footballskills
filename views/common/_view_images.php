<?php

use common\components\MyHelper;
use yii\helpers\Html;
use yii\helpers\Url;

?>

<div class="row">
    <?php if (!$model->isNewRecord) : ?>
        <?php foreach ($images as $image) : ?>
            <?php if ($image) : ?>
                <div class="col-sm-4 img-view">
                    <span class="pull-left btn btn-danger delete-image" style="position: absolute;">
                        <?= Html::a(
                            '<span class="glyphicon glyphicon-remove" style=" color: #fff;"></span>',
                            Url::to(['/' . MyHelper::uncamelCase($image->modelName) . '/image-delete', 'id_img' => $image->id,  'id_model' => $model->id])
                        ) ?>
                    </span>
                    <a href="<?= $image->getPath() ?>" data-rel="lightcase:g">
                        <?= Html::img($image->getPath('220')); ?>
                    </a>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
    <?php endif; ?>
</div>