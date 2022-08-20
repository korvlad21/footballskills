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
            <li>
                <a data-toggle="tab" href="#panel_portfolio_2">
                    <span>SEO</span>
                </a>
            </li>

        </ul>
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
            <div id="panel_portfolio_2" class="tab-pane fade">
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
    <div class="form-group text-right">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-info']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
</div>