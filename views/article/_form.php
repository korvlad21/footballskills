<?php

use common\models\ArticleCategory;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\widgets\ckeditor\CkeditorMy;
use kartik\date\DatePicker;
use kartik\datetime\DateTimePicker;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model common\models\Article */
/* @var $form yii\widgets\ActiveForm */

$modelName = "article";
$idForm = $model->isNewRecord ? 'form-article-create' : 'form-article-update';
?>

<div class="article-form">
    <?php $form = ActiveForm::begin(['id' => $idForm]); ?>
    <div class="tabs-block">
        <ul class="tabs-list clearfix">
            <li class="active">
                <a data-toggle="tab" href="#panel_article_1">
                    <span>Основные параметры</span>
                </a>
            </li>
            <li>
                <a data-toggle="tab" href="#panel_article_2">
                    <span>SEO</span>
                </a>
            </li>
        </ul>
        <div class="tab-content">
            <div id="panel_article_1" class="tab-pane fade in active">
                <div id="data-modal-article-form">
                    <div class="row">

                        <div class="col-sm-8">
                            <div class="col-sm-12">
                                <?= $form->field($model, 'name')->textInput() ?>
                                </div>
                            <div class="col-sm-12">
                                <?= $form->field($model, 'short_text')->textarea(['rows' => 5]) ?>
                            </div>
                            <div class="col-sm-12">
                                <?=
                                $form->field($model, 'article_category_id')->widget(Select2::class, [
                                    'data' => ArticleCategory::getListForSelect('name'),
                                    'options' => ['placeholder' => 'Выберите категорию', 'required' => true],
                                    'pluginOptions' => [
                                        'tabindex' => false,
                                        'tags' => false,
                                        'tokenSeparators' => [',', ' '],
                                    ],
                                ])->label('Категория');
                                ?>
                            </div>
                        </div>

                        <div class="col-sm-2">
                            <div class="pull-right pull-right-check">
                                <?php echo $form->field($model, 'visibility', ['options' => ['class' => 'form-group cust-checkbox'], 'template' => '<label> {input} <span class="cust-checkbox__box"></span> Опубликовать</label>'])->checkbox([], false);  ?>
                            </div>
                            <div class="col-sm-12">
                                <?= $form->field($model, 'image')->fileInput() ?>
                            </div>
                            <div class="col-sm-12">
                                <?php if (!empty($model->getImage()->itemId)) : ?>
                                    <a href="<?= $model->getImage()->getPath() ?>" data-rel="lightcase:g">
                                        <img src="<?= $model->getImage()->getPath('90x') ?>" alt="<?= htmlspecialchars($model->name) ?>">
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>

                    </div>

                    <div class="row">
                        <div class="col-sm-12">
                            <?= $form->field($model, 'text')->widget(CkeditorMy::class, []) ?>
                        </div>
                        <div class="col-sm-3">
                            <?= DatePicker::widget([
                                'model' => $model,
                                'attribute' => 'date',
                            ]); ?>
                        </div>

                    </div>

                </div>
            </div>
            <div id="panel_article_2" class="tab-pane fade">
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
    <div class="form-group text-left">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-flat btn-info']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>