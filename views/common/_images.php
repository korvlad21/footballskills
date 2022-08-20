
<?php
use yii\helpers\Html;
use yii\helpers\Url;
?>



<div class="row">
    <?php
    if (!$model->isNewRecord) :
        foreach ($images as $image):
            if ($image) :
                ?>
                <div class="col-sm-4">
                            <span class="pull-left btn btn-danger delete-image" style="position: absolute;">
                            <?= Html::a('<span class="glyphicon glyphicon-remove"style=" color: #fff;"></span>',
                                Url::to(['/page/image-delete', 'name' => $image->name]),['data-delete-image' => true]) ?>
                        </span>
                    <?= Html::img($image->getPath('200')); ?>
                </div>
            <?php
            endif;
        endforeach;
    endif;?>
</div>
<div class="row"></div>
