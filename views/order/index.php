<?php

use yii\helpers\Html;
use yii\grid\GridView;
use backend\assets\OrderAsset;


/* @var $this yii\web\View */
/* @var $searchModel common\models\search\OrderSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */


OrderAsset::register( $this );


$this->title = 'Заказы';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-index">
    <div class="box">
        <div class="box-header with-border">
            <div style="float: left">
                <h3 class="box-title"><?= Html::encode($this->title) ?></h3>
            </div>
            <div class="box-tools pull-right">

            </div>
        </div>
        <div class="box-body box-goods">
            <div id="tableUserdata">
                <?= $this->render('_table_order', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                ])?>
            </div>
        </div>
        <div class="box-footer">
            <div class="row">
                <div id="summaryOrders" class="col-lg-3">
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <th>Заказов всего: </th>
                                <td><?= $ordersCount; ?></td>
                            </tr>
    
                            <tr>
                                <th>Cумма стоимости заказов: </th>
                                <td><b><span class="rub"><?= Yii::$app->myHelper->getForntPrice( $ordersSumm ); ?></span></b></td>
                            </tr>
    
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
