<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;

$this->title = 'Клиенты';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class="col-sm-10">
        <div class="box">
            <div class="box-header with-border">
                <div style="float: left">
                    <h3 class="box-title"><?= Html::encode($this->title) ?></h3>
                </div>
                <div class="box-tools pull-right">
                    <p>
                        <?= Html::a('Добавить пользователя', ['create'], ['class' => 'btn btn-flat btn-info', 'data-userdata-create-button' => true]); ?>
                    </p>
                </div>
            </div>
            <div class="box-body">
                <div id="tableUserdata">
                    <?=

                    GridView::widget([
                        'dataProvider' => $dataProvider,
                        'filterModel' => $searchModel,
                        'options' => [
                            'style' => 'word-wrap: break-word;'
                        ],
                        'columns' => [
                            [
                                'attribute' => 'id',
                                'label' => 'Id',
                                'contentOptions' => ['style' => 'width:60px;  min-width:60px;  '],
                            ],

                            'email:email',
                            'name',
                            'phone',
                            'vendor_code',

                            [
                                'label' => 'Заказы',
                                'attribute' => 'user_fio',
                                'content' => function ($model) {

                                    return '<a href="' . Url::to([
                                        '/order', "OrderSearch[email]" => $model->email
                                    ]) . '">К заказам</a>';
                                }
                            ],

                            [
                                'class' => 'yii\grid\ActionColumn',
                                'template' => '{update}',
                            ],
                            [
                                'class' => \yii\grid\ActionColumn::class,
                                'template'=>'{delete}',
                                'visibleButtons' => [
                                    'delete' => true,

                                ],
                            ],
                        ],
                    ]);
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>