<?php

use common\models\Category;
use kartik\editable\Editable;
use kartik\grid\GridView;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

$this->title = Yii::t('app', 'Товары');
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="brand-index">
    <div class="row">
        <div class="col-sm-12 brand__tree">
            <div class="box">
            <div class="box-header with-border">
            </div>
                <div class="box-body" id="box-body-tree" style="min-height: 700px; max-height: 700px; overflow: auto">

                    <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                        'filterModel' => $searchModel,
                        'columns' => [

                            [
                                'label' => 'Картинка',
                                'format' => 'raw',
                                'value' => function ($model) {
                                    return Html::img($model->getImage()->getPath('50'), [
                                        'alt' => 'yii2 - картинка в gridview',
                                        'style' => 'width:50px;'
                                    ]);
                                },
                            ],

                            [
                                'attribute' => 'name',
                                'format' => 'row',
                                'content' => function ($model) {
                                    return Html::a($model->name, ['update', 'id' => $model->id]);
                                },
                            ],

                            'vendor_code',

                            [
                                'attribute' => 'category_id',
                                'options' => ['style' => 'width: 400px; max-width: 400px;'],
                                'label' => 'Категория',
                                'format' => 'raw',
                                'value' => function ($model) {
                                    $value = "";
                                    $modelCategory = $model->category;
                                    if (!empty($modelCategory)) {
                                        $value = $model->category->name;
                                    }
                                    return  $value;
                                },

                                'filter' =>  Select2::widget([
                                    'model' => $searchModel,
                                    'attribute' => 'category_id',
                                    'data' => Category::dropDown(),
                                    'value' => 'category_id',
                                    'options' => [
                                        'class' => 'form-control',
                                        'placeholder' => 'Выберите значение'
                                    ],
                                    'pluginOptions' => [
                                        'allowClear' => true,
                                        'selectOnClose' => true,
                                    ]
                                ])
                            ],


                            [
                                'attribute' => 'visibility',
                                'class' => '\kartik\grid\EditableColumn',
                                'editableOptions' => [
                                    'formOptions' => ['action' => ['/good/update-grid']],
                                    'header' => 'видимость',
                                    'inputType' => Editable::INPUT_CHECKBOX,
                                    'options' => [
                                        'class' => 'new_class',
                                        'label' => 'Опубликован',
                                    ],
                                    'pjaxContainerId' => 'pjax-table',
                                ],
                                'content' => function ($model) {
                                    return \common\models\AppModel::getListYesNo($model->visibility);
                                },
                                'format' => 'boolean',
                                'filter' => \common\models\AppModel::getListYesNo(),
                                'label' => 'Видимость',
                            ],

                            [
                                'class' => \yii\grid\ActionColumn::class,
                                'template' => ' {update} &nbsp; {delete}',
                                'visibleButtons' => [
                                    'delete' => true,
                                ],
                            ],
                        ],

                    ]); ?>

                </div>
            </div>
        </div>
    </div>
</div>