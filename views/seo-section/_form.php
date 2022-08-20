<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$modelName = "seo";
?>
<div class="seo-form">
    <?php $form = ActiveForm::begin(); ?>
    <div class="tabs-block">
        <ul class="tabs-list clearfix">
            <li class="active">
                <a data-toggle="tab" href="#panel-seo-1">
                    <span>Основные параметры</span>
                </a>
            </li>
        </ul>
        <div class="tab-content">
            <div id="panel-seo-1" class="tab-pane fade in active">
                <div id="data-modal-seo">
                    <div class="row">
                        <div class="col-sm-6">
                            <?= $form->field($model, 'name')->textInput() ?>
                        </div>
                        <div class="col-sm-6">
                            <?= $form->field($model, 'slug')->textInput(['maxlength' => true, 'onkeyup' => 'myVar.lenghtChar(this)']) ?>
                            <span class="coiuntCharPr">Кол-во символов: <span data-count-lenght='<?= $modelName?>-slug'><?= mb_strlen($model->slug)?></span></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <?= $form->field($model, 'title')->textInput(['maxlength' => true, 'onkeyup' => 'myVar.lenghtChar(this)']) ?>
                            <span class="coiuntCharPr">Кол-во символов: <span data-count-lenght='<?= $modelName?>-title'><?= mb_strlen($model->title)?></span></span>
                            <?= $form->field($model, 'keywords')->textInput(['maxlength' => true, 'onkeyup' => 'myVar.lenghtChar(this)']) ?>
                            <span class="coiuntCharPr">Кол-во символов: <span data-count-lenght='<?= $modelName?>-keywords'><?= mb_strlen($model->keywords)?></span></span>
                        </div>
                        <div class="col-sm-6">
                            <?= $form->field($model, 'description')->textarea(['rows' => 6, 'onkeyup' => 'myVar.lenghtChar(this)']) ?>
                            <span class="coiuntCharPr">Кол-во символов: <span data-count-lenght='<?= $modelName?>-description'><?= mb_strlen($model->description)?></span></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                             <?= $form->field($model, 'text')->textarea(['rows' => 6, 'onkeyup' => 'myVar.lenghtChar(this)']) ?>
                              <span class="coiuntCharPr">Кол-во символов: <span data-count-lenght='<?= $modelName?>-text'><?= mb_strlen($model->text)?></span></span>
                        </div>
                    </div>
                </div> 
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="btn-group">
            <div class="pull-left box-tools">
                <?= Html::submitButton('Сохранить', ['class' => 'btn btn-flat btn-info']) ?>
            </div>
            <div class="pull-right box-tools">
                <?php if(!empty($model->slug)):?>
                    <?= Html::a('Перейти к разделу', '/' . $model->slug, ['class' => 'btn btn-flat btn-info']); ?>
                <?php endif;?>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>
