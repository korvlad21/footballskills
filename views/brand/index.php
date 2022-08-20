<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use common\models\Brand;

\kartik\select2\Select2Asset::register($this);


$this->title = 'Бренды';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="brand-index">
    <div class="row">
        <div class="col-sm-8 brand__tree">
            <div class="box">
                <div class="box-header with-border">
                    <div style="float: left">
                        <h3 class="box-title">Все бренды</h3>
                    </div>
                </div>
                <div class="box-body" id="box-body-tree" style="min-height: 700px; max-height: 700px; overflow: auto">
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
                                'attribute' => 'name',
                                'content' => function ($model) {
                                    return '<a href="' . Url::to(['update', 'id' => $model->id]) . '">' . $model->name . '</a>';
                                }
                            ],

                            'attribute' => 'description',

                            [
                                'class' => 'yii\grid\ActionColumn',
                                'template' => '{update}',
                            ],
                        ],
                    ]);
                    ?>
                </div>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="row">
                <?php $modelNew = new Brand(); ?>
                <div class="box">
                    <div class="box-header with-border">
                        <div style="float: left">
                            <h3 class="box-title">Новый бренд</h3>
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