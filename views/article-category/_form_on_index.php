<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>

<div class="portfolio-form">

    <?php $form = ActiveForm::begin(['action' => '/icms/article-category/create']); ?>

    <div class="tabs-block">
       
        <div class="tab-content">
            <div id="panel-portfolio-1" class="tab-pane fade in active">
                <div id="data-modal-portfolio-main">
                    <div class="row">

                        <div class="col-sm-12">
                            <?= $form->field($model, 'name')->textInput() ?>
                        </div>
                        <div class="col-sm-12">
                            <?= $form->field($model, 'short_text')->textarea(['rows' => 5]) ?>
                        </div>
                        
                    </div>

                    <div class="row">
                        <div class="col-sm-3">
                            <?= $form->field($model, 'prev')->fileInput(['accept' => "image/jpeg, image/png"])->label('Изображение') ?>
                            <?php
                            $images[] = $model->getImage();
                            if (!empty($images)) {
                                print $this->render('../common/_view_images', compact('images', 'model'));
                            }
                            ?>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
    <div class="form-group text-right">
        <?= Html::submitButton('Добавить', ['class' => 'btn btn-info']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
</div>