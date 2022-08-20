<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use common\models\ArticleCategory;

\kartik\select2\Select2Asset::register($this);


$this->title = 'Разделы новостей';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="brand-index">
    <div class="row">
        <div class="col-sm-7 brand__tree">
            <div class="box">
                <div class="box-header with-border">
                    <div style="float: left">
                        <h3 class="box-title">Все разделы</h3>
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
                                'attribute' => 'name',
                                'content' => function ($model) {
                                    return '<a href="' . Url::to(['update', 'id' => $model->id]) . '">' . $model->name . '</a>';
                                }
                            ],

                            [
                                'attribute' => 'short_text',
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
                    <?php $modelNew = new ArticleCategory(); ?>
                    <div class="box">
                        <div class="box-header with-border">
                            <div style="float: left">
                                <h3 class="box-title">Новый раздел</h3>
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