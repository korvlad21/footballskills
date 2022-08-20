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

                        <div class="col-sm-12">
                            <?= $form->field($model, 'name')->textInput([]) ?>
                        </div>
                        <div class="col-sm-12">
                            <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>
                        </div>

                    </div>

                    <div class="row">
                        <div class="col-sm-3">
                            <?= $form->field($model, 'image')->fileInput(['accept' => "image/jpeg, image/png"])->label('Изображение') ?>
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
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-info']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
</div>