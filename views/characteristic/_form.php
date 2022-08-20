<?php

use common\models\Units;
use common\widgets\ckeditor\CkeditorMy;
use kartik\file\FileInput;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

?>

<div class="page-form">
    <div class="row">
        <div class="col-sm-5">
            <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

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
                        <div class="row">
                            <div class="col-sm-12">
                                <?= $form->field($model, 'name')->textInput(['maxlength' => true,]) ?>
                                <?=
                                $form->field($model, 'unitsArray')->widget(Select2::class, [
                                    'data' => Units::getListForSelect(),
                                    'options' => ['placeholder' => 'Выберите единицы измерения', 'multiple' => true],
                                    'pluginOptions' => [
                                        'allowClear' => true,
                                        'tabindex' => false,
                                        'tags' => false,
                                        'tokenSeparators' => [',', ' '],
                                    ],
                                ])->label('Единицы измерения');
                                ?>
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

</div>