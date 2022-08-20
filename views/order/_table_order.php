<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use common\models\Order;

?>
<?=
GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'columns' => [
        [
            'attribute' => 'number',
            'width' => '75px',
        ],
        [
            'class' => 'kartik\grid\ExpandRowColumn',
            'width' => '50px',
            'value' => function ($model, $key, $index, $column) {
                return GridView::ROW_COLLAPSED;
            },
            'detail' => function ($model, $key, $index, $column) {
                return Yii::$app->controller->renderPartial('view', ['model' => $model]);
            },
            'headerOptions' => ['class' => 'kartik-sheet-style'],
            'expandOneOnly' => true
        ],

        [
            'attribute' => 'order_status',
            'label' => 'Статус',

            'value' => function ($model) {
                return $model->getStatusOrder($model->order_status);
            },
            'filter' => Order::getListForSelectStatusOrder()
        ],

        'user_name',
        'user_phone',
        'email:email',
        [
            'attribute' => 'created_at',
            'value' => function ($model) {
                return date('d.m.Y H:i', $model->created_at);
            },
            'filter' => kartik\daterange\DateRangePicker::widget([
                'model' => $searchModel,
                'attribute' => 'created_at',
                'convertFormat' => true,
                'useWithAddon' => true,
                'language' => 'ru',
                'hideInput' => true,
                'presetDropdown' => true,
                'startAttribute' => 'date_start',
                'endAttribute' => 'date_end',
                'pluginOptions' => [
                    'locale' => ['format' => 'd.m.Y', 'cancelLabel' => 'Очистить'], // from demo config
                    'separator' => '-',
                    'opens' => 'left',
                    'showDropdowns' => true
                ],
                'pluginEvents' => [
                    "cancel.daterangepicker" => "function(ev, picker) {
                        $('#orderssearch-created_at').val('').trigger('change');
                    }",
                ],
            ]),
        ],

        [
            'attribute' => 'sum_price_total',
            'value' => function ($model) {
                return Yii::$app->myHelper->getForntPrice($model->sum_price_total);
            }
        ],
    ],
]);
?>