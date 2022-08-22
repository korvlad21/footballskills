<?php

use app\models\Characteristic;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\search\CharacteristicSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Категории характеристик';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="brand-index">
    <div class="row">
        <div class="col-sm-12 brand__tree">
            <div class="box">
                <div class="box-header with-border">
                    <div style="float: left">
                        <h3 class="box-title"><?= Html::encode($this->title) ?></h3>
                    </div>
                    <div class="pull-right">
                        <?= Html::a('Добавить', ['create'], ['class' => 'btn btn-flat btn-info']); ?>
                    </div>
                </div>
                <div class="box-body" id="box-body-tree" style="min-height: 700px; max-height: 700px; overflow: auto">
                    <?//php Pjax::begin(); ?>
                    <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                        'filterModel' => $searchModel,
                        'columns' => [
                            [
                                'attribute' => 'id',
                                'options' => ['style' => 'width: 60px; max-width: 60px;'],
                            ],
                            [
                                'attribute' => 'name',
                                'format' => 'row',
                                'content' => function ($model) {
                                    return Html::a($model->name, ['update', 'id' => $model->id]);
                                },
                            ],

                            [
                                'attribute' => 'parent_id',
                                'options' => ['style' => 'width: 400px; max-width: 400px;'],
                                'label' => 'Родитель',
                                'format' => 'raw',
                                'value' => function ($model) {
                                    $value = "";
                                    $modelParent = $model->parent;
                                    if (!empty($modelParent)) {
                                        $value = $model->parent->name;
                                    }
                                    return  $value;
                                },

                                'filter' =>  Select2::widget([
                                    'model' => $searchModel,
                                    'attribute' => 'parent_id',
                                    'data' => Characteristic::dropDown(),
                                    'value' => 'id',
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
                                'class' => \yii\grid\ActionColumn::class,
                                'template' => '{update} {delete}',
                            ],
                        ],
                    ]); ?>

                    <?//php Pjax::end(); ?>

                </div>
            </div>
        </div>

    </div>
</div>