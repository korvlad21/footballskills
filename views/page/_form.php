<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\widgets\ckeditor\CkeditorMy;
use kartik\datetime\DateTimePicker;
use mihaildev\elfinder\ElFinder;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model common\models\Page */
/* @var $form yii\widgets\ActiveForm */

$modelName = "page";
?>

<div class="page-form">
    <?php $form = ActiveForm::begin(); ?>
    <div class="tabs-block">
        <ul class="tabs-list clearfix">
            <li class="active">
                <a data-toggle="tab" href="#panel1">
                    <span>Основные параметры</span>
                </a>
            </li>
            <li>
                <a data-toggle="tab" href="#panel2">
                    <span>SEO</span>
                </a>
            </li>
        </ul>
        <div class="tab-content">
            <div id="panel1" class="tab-pane fade in active">
                <div id="data-modal-good">
                    <div class="row">
                        <div class="col-sm-8">
                            <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
                        </div>

                        <div class="col-sm-2">
                            <div class="pull-right pull-right-check">
                                <?php echo $form->field($model, 'visibility', ['options' => ['class' => 'form-group cust-checkbox'], 'template' => '<label> {input} <span class="cust-checkbox__box"></span> Опубликовать</label>'])->checkbox([],false);  ?>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <?= $form->field($model, 'text_one')->widget(CkeditorMy::class, []) ?>
                        </div>

                        <!-- <div class="col-sm-12">
                            <?= $form->field($model, 'dateTime')->widget(DateTimePicker::classname(), [
                                'options' => ['placeholder' => 'Введите время ...'],
                                'pluginOptions' => [
                                    'convertFormat' => true,
                                    'autoclose' => true,
                                    'format' => 'dd.mm.yyyy hh:ii',
                                    'language' => 'ru',
                                    'weekStart'=>1, //неделя начинается с понедельника
                                    'todayBtn'=>true, //снизу кнопка "сегодня"
                                ]]); ?>
                        </div> -->




                        <div class="col-sm-12">
                            <?php //echo $form->field($model, 'text_two')->widget(CkeditorMy::class, []) ?>
                        </div>
                        <div class="col-sm-12">
                            <?php //echo $form->field($model, 'text_three')->widget(CkeditorMy::class, []) ?>
                        </div>
                    </div>

                </div> 
            </div>
            <div id="panel2" class="tab-pane fade">
                <div id="data-modal">
                    <div class="row">
                        <div class="col-sm-6">
                            <?= $form->field($seo, 'h1')->textInput(['maxlength' => true, 'onkeyup' => 'myVar.lenghtChar(this)']) ?>
                            <span class="coiuntCharPr">Кол-во символов: <span data-count-lenght='<?= $modelName?>-h1'><?= mb_strlen($model->h1)?></span></span>
                            <?= $form->field($seo, 'title')->textInput(['maxlength' => true, 'onkeyup' => 'myVar.lenghtChar(this)']) ?>
                            <span class="coiuntCharPr">Кол-во символов: <span data-count-lenght='<?= $modelName?>-title'><?= mb_strlen($model->title)?></span></span>
                            <?= $form->field($model, 'slug')->textInput(['maxlength' => true, 'onkeyup' => 'myVar.lenghtChar(this)']) ?>
                            <span class="coiuntCharPr">Кол-во символов: <span data-count-lenght='<?= $modelName?>-slug'><?= mb_strlen($model->slug)?></span></span>
                            
                        </div>
                        <div class="col-sm-6">
                            <?= $form->field($seo, 'keywords')->textInput(['maxlength' => true, 'onkeyup' => 'myVar.lenghtChar(this)']) ?>
                            <span class="coiuntCharPr">Кол-во символов: <span data-count-lenght='<?= $modelName?>-keywords'><?= mb_strlen($model->keywords)?></span></span>
                            <?= $form->field($seo, 'description')->textarea(['rows' => 4, 'onkeyup' => 'myVar.lenghtChar(this)']) ?>
                            <span class="coiuntCharPr">Кол-во символов: <span data-count-lenght='<?= $modelName?>-description'><?= mb_strlen($model->description)?></span></span>
                        </div>
                    </div>
                </div> 
            </div>
        </div>
    </div>
    <div class="form-group text-left">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-flat btn-info']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
