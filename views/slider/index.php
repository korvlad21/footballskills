<?php

use common\models\Partners;
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;
use common\models\Slider;
use kartik\editable\Editable;

\kartik\select2\Select2Asset::register($this);


$this->title = 'Слайдер';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="brand-index">
    <div class="row">
        <div class="col-sm-7 brand__tree">
            <div class="box">
                <div class="box-header with-border">
                    <div style="float: left">
                        <h3 class="box-title">Все слайды</h3>
                    </div>
                </div>
                <div class="box-body" id="box-body-tree" style="min-height: 600px; max-height: 600px; overflow: auto">
                    <?=
                    GridView::widget([
                        'dataProvider' => $dataProvider,
                        'filterModel' => $searchModel,
                        'options' => [
                            'style' => 'word-wrap: break-word;'
                        ],
                        'columns' => [
                            [
                                'label' => 'Картинка',
                                'format' => 'raw',
                                'value' => function ($model) {
                                    return Html::img(Url::toRoute($model->getImage()->getPath('x150')), [
                                        'alt' => 'yii2 - картинка в gridview',
                                        'style' => 'width:150px;'
                                    ]);
                                },
                            ],
                            [
                                'label' => '#',
                                'attribute' => 'id',
                            ],

                            [
                                'attribute' => 'name',
                                'content' => function ($model) {
                                    return '<a href="' . Url::to(['update', 'id' => $model->id]) . '">' . $model->name . '</a>';
                                }
                            ],
                            [
                                'attribute' => 'visibility',
                                'class' => '\kartik\grid\EditableColumn',
                                'editableOptions' => [
                                    'formOptions' => ['action' => ['/slider/update-grid']],
                                    'header' => 'видимость',
                                    'inputType' => Editable::INPUT_CHECKBOX,
                                    'options' => [
                                        'class' => 'new_class',
                                        'label' => 'Опубликован',
                                    ],
                                    'pjaxContainerId' => 'pjax-table',
                                ],
                                'content' => function ($model) {
                                    return $model::getListYesNo($model->visibility);
                                },
                                'format' => 'boolean',
                                'filter' => Slider::getListYesNo(),
                                'label' => 'Опубликован',
                            ],
                            [
                                'class' => 'yii\grid\ActionColumn',
                                'template' => '{update} &nbsp; {delete}',
                            ],
                        ],
                    ]);
                    ?>
                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="ge-create">
                <div class="row">
                    <?php $modelNew = new Slider(); ?>
                    <div class="box">
                        <div class="box-header with-border">
                            <div style="float: left">
                                <h3 class="box-title">Новый слайд</h3>
                            </div>
                        </div>
                        <div class="box-body">
                            <?= $this->render('_form_on_index', ['model' => $modelNew]) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>