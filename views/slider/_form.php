<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>

<div class="portfolio-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="tabs-block">
        <ul class="tabs-list clearfix">
            <li class="active">
                <a data-toggle="tab" href="#panel-portfolio-1">
                    <span>Основные параметры</span>
                </a>
            </li>

        </ul>
        <div class="tab-content">
            <div id="panel-portfolio-1" class="tab-pane fade in active">
                <div id="data-modal-portfolio-main">

                    <div class="row">
                        <div class="col-sm-7">
                            <?= $form->field($model, 'name')->textInput() ?>
                            <?= $form->field($model, 'link')->textInput() ?>
                        </div>
                        <div class="col-sm-5">
                            <div class="pull-right pull-right-check">
                                <?php echo $form->field($model, 'visibility', ['options' => ['class' => 'form-group cust-checkbox'], 'template' => '<label> {input} <span class="cust-checkbox__box"></span> Опубликовать</label>'])->checkbox([], false);  ?>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-4">
                            <?= $form->field($model, 'prev')->fileInput(['accept' => "image/jpeg, image/png"])->label('Изображение (1920x450)') ?>
                            <?php
                            $image = $model->getImageByName('slider_desktop');
                            if (!empty($image)) {
                                print $this->render('../common/_view_image', compact('image', 'model'));
                            }
                            ?>
                        </div>
                        <div class="col-sm-4">
                            <?= $form->field($model, 'prev_tablet')->fileInput(['accept' => "image/jpeg, image/png"])->label('Изображение (1280x300)') ?>
                            <?php
                            $image = $model->getImageByName('slider_tablet');
                            if (!empty($image)) {
                                print $this->render('../common/_view_image', compact('image', 'model'));
                            }
                            ?>
                        </div>

                    </div>

                </div>
            </div>

        </div>
    </div>
    <div class="form-group text-right">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-info']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
</div>