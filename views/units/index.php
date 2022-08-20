<?php

use common\models\GoodCharacteristic;
use common\models\Units;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel common\models\search\PageSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Единицы измерения');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="brand-index">
    <div class="row">
        <div class="col-sm-8 brand__tree">
            <div class="box">
                <div class="box-header with-border">
                    <div style="float: left">
                        <h3 class="box-title"><?= $this->title ?></h3>
                    </div>
                    <div class="pull-right">
                        <!-- <?= Html::a('Добавить', ['create'], ['class' => 'btn btn-flat btn-info']); ?> -->
                    </div>
                </div>
                <div class="box-body" id="box-body-tree" style="min-height: 700px; max-height: 700px; overflow: auto">

                    <?php Pjax::begin(); ?>
                    <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                        'filterModel' => $searchModel,
                        'columns' => [
                            [
                                'attribute' => 'id',
                                'label' => 'Id',
                                'contentOptions' => ['style' => 'width:60px;  min-width:60px;  '],
                            ],

                            [
                                'attribute' => 'name',
                                'format' => 'row',
                                'content' => function ($model) {
                                    return Html::a($model->name, ['update', 'id' => $model->id]);
                                },
                            ],

                            [
                                'class' => \yii\grid\ActionColumn::class,
                                'template' => '{delete}',
                                'visibleButtons' => [
                                    'delete' => true,

                                ],
                            ],
                        ],
                    ]); ?>
                    <?php Pjax::end(); ?>

                </div>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="row">
                <?php $modelNew = new Units(); ?>
                <div class="box">
                    <div class="box-header with-border">
                        <div style="float: left">
                            <h3 class="box-title">Новая единица измерения</h3>
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