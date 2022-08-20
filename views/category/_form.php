<?php

use common\models\Category;
use common\widgets\ckeditor\CkeditorMy;
use kartik\file\FileInput;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Good */
/* @var $form yii\widgets\ActiveForm */


?>

<div class="pick-point-form">
    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <div class="tabs-block">

        <ul class="tabs-list clearfix">
            <li class="active">
                <a data-toggle="tab" href="#panel_1" class="active" href="">Основные параметры</a>
            </li>
            <li class="">
                <a data-toggle="tab" href="#panel-portfolio-2" tabname="panel-portfolio-2">
                    <span>SEO</span>
                </a>
            </li>
        </ul>

        <div class="tab-content">
            <div id="panel_1" class="tab-pane fade in active">
                <div class="row">
                    <div class="col-lg-8">

                        <div class="col-lg-8">
                            <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
                            <?=
                            $form->field($model, 'parent_id')->widget(
                                Select2::class,
                                [
                                    'data' => Category::getListForSelectInCategory($model->id),
                                    'options' => ['placeholder' => 'Выбор родительской категории'],
                                    'pluginOptions' => [
                                        'tabindex' => false,
                                        'tags' => false,
                                    ],
                                ]
                            )->label('Родительская категория');
                            ?>

                            <div class="col-lg-4">
                                <?= $form->field($model, 'count_small_start')->textInput(['type' => 'number']) ?>
                            </div>
                            <div class="col-lg-4">
                                <?= $form->field($model, 'count_medium_start')->textInput(['type' => 'number']) ?>
                            </div>
                            <div class="col-lg-4">
                                <?= $form->field($model, 'count_big_start')->textInput(['type' => 'number']) ?>
                            </div>
                        </div>

                        <div class="col-sm-4">
                            <?= $this->render('_tree_category', ['model' => $model]) ?>
                        </div>


                    </div>

                    <div class="col-lg-4">
                        <?= $this->render('_filters', ['model' => $model]) ?>
                    </div>

                </div>
            </div>

            <div id="panel-portfolio-2" class="tab-pane fade">
                <div class="row">
                    <div class="col-lg-12">
                        <?= $form->field($model, 'slug')->textInput()->label('URL'); ?>
                        <div id="data-modal-article-seo">
                            <div class="row">
                                <div class="col-sm-6">
                                    <?= $form->field($model, 'slug')->textInput(['readonly' =>true]) ?>
                                    <?= $form->field($seo, 'h1')->textInput(['onkeyup' => 'myVar.lenghtChar(this)']) ?>
                                    <span class="coiuntCharPr">Кол-во символов: <span data-count-lenght='seo-h1'><?= mb_strlen($seo->h1) ?></span></span>
                                    <?= $form->field($seo, 'title')->textInput(['onkeyup' => 'myVar.lenghtChar(this)']) ?>
                                    <span class="coiuntCharPr">Кол-во символов: <span data-count-lenght='seo-title'><?= mb_strlen($seo->title) ?></span></span>
                                </div>
                                <div class="col-sm-6">
                                    <?= $form->field($seo, 'keywords')->textInput(['onkeyup' => 'myVar.lenghtChar(this)']) ?>
                                    <span class="coiuntCharPr">Кол-во символов: <span data-count-lenght='seo-keywords'><?= mb_strlen($seo->keywords) ?></span></span>
                                    <?= $form->field($seo, 'description')->textarea(['rows' => 4, 'onkeyup' => 'myVar.lenghtChar(this)']) ?>
                                    <span class="coiuntCharPr">Кол-во символов: <span data-count-lenght='seo-description'><?= mb_strlen($seo->description) ?></span></span>
                                </div>
                            </div>
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