<?php

use app\models\Characteristic;
use app\widgets\ckeditor\CkeditorMy;
use kartik\file\FileInput;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Characteristic */
/* @var $form yii\widgets\ActiveForm */


?>

<div class="pick-point-form">
    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <div class="tabs-block">

        <ul class="tabs-list clearfix">
            <li class="active">
                <a data-toggle="tab" href="#panel_1" class="active" href="">Основные параметры</a>
            </li>
        </ul>

        <div class="tab-content">
            <div id="panel_1" class="tab-pane fade in active">
                <div class="row">
                    <div class="col-lg-8">

                        <div class="col-lg-8">
                            <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
                            <?= $form->field($model, 'description')->textarea(['rows' => 5]) ?>
                            <?=
                            $form->field($model, 'parent_id')->widget(
                                Select2::class,
                                [
                                    'data' => Characteristic::getListForSelectInCharacteristic($model),
                                    'options' => ['placeholder' => 'Выбор родительской категории'],
                                    'pluginOptions' => [
                                        'tabindex' => false,
                                        'tags' => false,
                                    ],
                                ]
                            )->label('Родительская категория');
                            ?>

                           
                        </div>

                        <div class="col-sm-4">
                            <?= $this->render('_tree_characteristic', ['model' => $model]) ?>
                        </div>


                    </div>

                </div>
            </div>

        </div>

        <div class="form-group">
            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-flat btn-info']) ?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>