<?php

use app\models\Characteristic;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\search\CharacteristicPlayerSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->title = 'Характеристики игроков';
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
                </div>
                <div class="box-body" id="box-body-tree" style="min-height: 700px; max-height: 700px; overflow: auto">
                    <?//php Pjax::begin(); ?>
                    <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                        'filterModel' => $searchModel,
                        'columns' => [  
                            [
                                'attribute' => 'player_id',
                                'label' => 'Игрок',
                                'filter' => false,
                                'value' => function ($model) {
                                    $value = "";
                                    $modelPlayer = $model->player;
                                    if (!empty($modelPlayer)) {
                                        $value = $modelPlayer->surname. ' '.substr($modelPlayer->name, 0, 2).'. '.substr($modelPlayer->otchestvo, 0, 2).'.';
                                    }
                                    return  $value;
                                },
                            ],

                            [
                                'attribute' => 'characteristic_id',
                                'options' => ['style' => 'width: 400px; max-width: 400px;'],
                                'label' => 'Характеристика',
                                'format' => 'raw',
                                'value' => function ($model) {
                                    $value = "";
                                    $modelCharact = $model->characteristic;
                                    if (!empty($modelCharact)) {
                                        $value = $modelCharact->name;
                                    }
                                    return  $value;
                                },

                                'filter' =>  Select2::widget([
                                    'model' => $searchModel,
                                    'attribute' => 'characteristic_id',
                                    'data' => Characteristic::getListForSelectChild('name'),
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
                                'attribute' => 'value',
                                'label' => 'Показатель',
                                'filter' => false,
                                
                            ],

                          
                        ],
                    ]); ?>

                    <?//php Pjax::end(); ?>

                </div>
            </div>
        </div>

    </div>
</div>