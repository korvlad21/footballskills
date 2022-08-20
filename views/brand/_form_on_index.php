<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>

<div class="portfolio-form">

    <?php $form = ActiveForm::begin(['action' => '/icms/brand/create']); ?>

    <div class="tabs-block">

        <div class="tab-content">
            <div id="panel-portfolio-1" class="tab-pane fade in active">
                <div id="data-modal-portfolio-main">

                    <div class="row">
                        <div class="col-sm-12">
                            <?= $form->field($model, 'name')->textInput() ?>
                            <?= $form->field($model, 'description')->textarea(['rows' => 5,]) ?>
                            <?= $form->field($model, 'image')->fileInput(['accept' => "image/jpeg, image/png"])->label('Изображение') ?>
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