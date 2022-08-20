<?php

use common\models\Brand;
use common\models\Category;
use common\models\GoodParams;
use common\widgets\ckeditor\CkeditorMy;
use dosamigos\ckeditor\CKEditor;
use kartik\file\FileInput;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$this->registerCssFile('/icms/css/good.css');
$this->registerJsFile('/icms/js/good.js');

$seo = $model->getSeo();

?>

<div class="page-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <div class="tabs-block">

        <ul class="tabs-list clearfix">
            <li class="active">
                <a data-toggle="tab" href="#panel-portfolio-1" tabname="panel-portfolio-1">
                    <span>Основные параметры</span>
                </a>
            </li>
            <li class="">
                <a data-toggle="tab" href="#panel-characts" tabname="panel-characts">
                    <span>Характеристики</span>
                </a>
            </li>
            <li class="">
                <a data-toggle="tab" href="#panel-images" tabname="panel-images">
                    <span>Изображения</span>
                </a>
            </li>
            <li class="">
                <a data-toggle="tab" href="#panel-documents" tabname="panel-documents">
                    <span>Документы</span>
                </a>
            </li>
            <li class="">
                <a data-toggle="tab" href="#panel-portfolio-2" tabname="panel-portfolio-2">
                    <span>SEO</span>
                </a>
            </li>
        </ul>

        <div class="tab-content">

            <div id="panel-portfolio-1" class="tab-pane fade in active">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="col-sm-8">
                            <div class="col-sm-12">
                                <?= $form->field($model, 'name')->textInput(['maxlength' => true,]) ?>
                                <?= $form->field($model, 'vendor_name')->textInput(['maxlength' => true, 'readonly' => true]) ?>
                            </div>
                            <div class="col-sm-6">
                                <?=
                                $form->field($model, 'category_id')->widget(Select2::class, [
                                    'data' => Category::getListForSelectInGood(),
                                    'options' => ['placeholder' => 'Выберите категорию'],
                                    'pluginOptions' => [
                                        'tabindex' => false,
                                        'tags' => false,
                                        'tokenSeparators' => [',', ' '],
                                    ],
                                ]);
                                ?>
                            </div>
                            <div class="col-sm-6">
                                <?=
                                $form->field($model, 'brand_id')->widget(Select2::class, [
                                    'data' => Brand::getListForSelect('name'),
                                    'options' => ['placeholder' => 'Выберите бренд'],
                                    'pluginOptions' => [
                                        'tabindex' => false,
                                        'tags' => false,
                                        'tokenSeparators' => [',', ' '],
                                    ],
                                ]);
                                ?>
                            </div>
                            <div class="col-sm-12">
                                <?= $form->field($model, 'video_link')->textInput(['maxlength' => true,]) ?>
                                <?= $form->field($model, 'description_title')->textInput(['maxlength' => true,]) ?>
                                <?= $form->field($model, 'description')->widget(CkeditorMy::class, []) ?>
                            </div>

                        </div>
                        <div class="col-sm-4">

                            <div class="row">

                                <div class="row">

                                    <div class="col-sm-12">
                                        <div class="col-sm-6">
                                            <div class="col-sm-12 pull-left pull-left-check">
                                                <?php echo $form->field($model, 'label_hit', ['options' => ['class' => 'form-group cust-checkbox'], 'template' => '<label> {input} <span class="cust-checkbox__box"></span> Хит </label>'])->checkbox([], false);  ?>
                                            </div>
                                            <div class="col-sm-12 pull-left pull-left-check">
                                                <?php echo $form->field($model, 'label_new', ['options' => ['class' => 'form-group cust-checkbox'], 'template' => '<label> {input} <span class="cust-checkbox__box"></span> Новинка </label>'])->checkbox([], false);  ?>
                                            </div>
                                            <div class="col-sm-12 pull-left pull-left-check">
                                                <?php echo $form->field($model, 'label_sale', ['options' => ['class' => 'form-group cust-checkbox'], 'template' => '<label> {input} <span class="cust-checkbox__box"></span> Распродажа </label>'])->checkbox([], false);  ?>
                                            </div>
                                            <div class="col-sm-12 pull-left pull-left-check">
                                                <?php echo $form->field($model, 'label_storage', ['options' => ['class' => 'form-group cust-checkbox'], 'template' => '<label> {input} <span class="cust-checkbox__box"></span> Складская программа </label>'])->checkbox([], false);  ?>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="col-sm-12 pull-left pull-left-check">
                                                <?php echo $form->field($model, 'visibility', ['options' => ['class' => 'form-group cust-checkbox'], 'template' => '<label> {input} <span class="cust-checkbox__box"></span> Опубликован </label>'])->checkbox([], false);  ?>
                                            </div>
                                            <div class="col-sm-12 pull-left pull-left-check">
                                                <?php echo $form->field($model, 'is_buffer', ['options' => ['class' => 'form-group cust-checkbox'], 'template' => '<label> {input} <span class="cust-checkbox__box"></span> Буферный товар </label>'])->checkbox([], false);  ?>
                                            </div>
                                            <div class="col-sm-12 pull-left pull-left-check">
                                                <?php echo $form->field($model, 'is_main', ['options' => ['class' => 'form-group cust-checkbox'], 'template' => '<label> {input} <span class="cust-checkbox__box"></span> На главной </label>'])->checkbox([], false);  ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="col-sm-6">
                                            <?= $form->field($model, 'price')->textInput(['type' => 'number', 'step' => "0.01", 'required' => true]) ?>
                                            <?= $form->field($model, 'vendor_code')->textInput(['maxlength' => true, 'readonly' => true]) ?>
                                        </div>
                                        <div class="col-sm-6">
                                            <?= $form->field($model, 'old_price')->textInput(['type' => 'number', 'step' => "0.01"]) ?>
                                            <?= $form->field($model, 'units')->textInput(['type' => 'text']) ?>
                                        </div>
                                        <div class="col-sm-12">
                                            <?php if (!empty($model->goodAvability)) : ?>
                                                <div class="table-avability">
                                                    <table class="table table-sm">
                                                        <thead class="thead-light">
                                                            <tr>
                                                                <th scope="col">Город</th>
                                                                <th scope="col">Фирма</th>
                                                                <th scope="col">Количество</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php foreach ($model->goodAvability as $avability) :  ?>
                                                                <tr>
                                                                    <td><?= $avability->city->name ?></td>
                                                                    <td><?= $avability->firm_name ?></td>
                                                                    <td><?= $avability->quantity ?></td>
                                                                </tr>
                                                            <?php endforeach; ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div id="panel-characts" class="tab-pane fade">
                <div class="row">
                    <div class="col-sm-12">
                        <?php if (!empty($model->category_id)) : ?>
                            <?= $this->render('_characterictics', ['model' => $model]) ?>
                        <?php else : ?>
                            <h5><b>Для заполнения характеристик сохраните товар с привязкой к категории</b></h5>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div id="panel-images" class="tab-pane fade">
                <div class="row">
                    <div class="col-lg-12">
                        <?php echo $form->field($model, 'imgs[]')->widget(FileInput::class, [
                            'options' => ['multiple' => true, 'accept' => 'image/*'],
                            'pluginOptions' => [
                                'maxFileSize' => 20000
                            ]
                        ]); ?>
                        <?= $form->field($model, 'mainImage')->dropDownList($model->getPhotosList()) ?>
                        <?php
                        $images = $model->getImages();
                        if (!empty($images)) {
                            echo $this->render('../common/_view_images_good', compact('images', 'model'));
                        }
                        ?>
                    </div>
                </div>
            </div>

            <div id="panel-documents" class="tab-pane fade">
                <div class="row">
                    <div class="col-lg-12">
                        <?= $form->field($model, 'loadFile')->fileInput()->label('Прикрепить файл') ?>
                        <?php $docs = $model->documents; ?>
                        <?php if (!empty($docs)) : ?>
                            <?php $i = 1; ?>
                            <?php foreach ($docs as $doc) : ?>
                                <a href="<?= $doc->file_path ?>"> <b><?= $doc->fileName ?></b></a>
                                <?= Html::a('[Удалить]', Url::to(['/good/delete-file', 'id' => $doc->id]), ['class' => 'delete-file']) ?><br>
                                <?php $i++; ?>
                            <?php endforeach; ?>
                        <?php endif; ?>

                    </div>
                </div>
            </div>

            <div id="panel-portfolio-2" class="tab-pane fade">
                <div class="row">
                    <div class="col-lg-12">
                        <?= $form->field($model, 'slug')->textInput()->label('URL'); ?>
                        <? //= $this->render('../common/_seo', ['model' => $model->getSeo()]); 
                        ?>
                    </div>
                </div>
            </div>

        </div>

        <div class="good-btns form-group text-right">
            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-info']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

    <div class="good-form">
        <?php $form = ActiveForm::begin(); ?>
        <?php ActiveForm::end(); ?>
    </div>
</div>