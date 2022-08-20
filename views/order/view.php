<?php

use common\models\OrderGoods;
use common\models\Good;
use common\models\Order;
use common\models\GoodPricelist;
use yii\helpers\Html;
use yii\helpers\Url;


?>
<div class="order-view">
    <div class="row">
        <div class="col-sm-6">
            <div class="box">
                <div class="box-header">

                </div>
                <div class="box-body" style="font-size: 0;" data-order-describe="true" data-id="<?= $model->id; ?>">
                    <br>
                    <table style="font-size: 14px;" class="table table-bordered table-striped kv-table-wrap" data-order-describe="true">

                        <tr>
                            <td colspan="2"><b>Информация о доставке</b></td>
                        </tr>
                        <tr>
                            <td>Способ доставки</td>
                            <td><?= $model->type_courier_delivery ?></td>
                        </tr>

                        <?php if (($model->transport_type == "courier") || ($model->transport_type == "owncourier")) : ?>
                            <!-- Доставка -->
                            <tr>
                                <td>Город</td>
                                <td><?= !empty($model->order_city) ? $model->order_city : 'Не указано' ?></td>
                            </tr>
                            <tr>
                                <td>Улица</td>
                                <td><?= !empty($model->order_street) ? $model->order_street : 'Не указано' ?></td>
                            </tr>
                            <tr>
                                <td>Дом</td>
                                <td><?= !empty($model->order_home) ? $model->order_home : 'Не указано' ?></td>
                            </tr>
                            <tr>
                                <td>Квартира</td>
                                <td><?= !empty($model->order_apartment) ? $model->order_apartment : 'Не указано' ?></td>
                            </tr>
                        <?php else : ?>
                            <tr>
                                <td>Пункт самовывоза</td>
                                <td><?= $model->addressOrder ?></td>
                            </tr>
                        <?php endif; ?>

                        <tr>
                            <td>Предпочтительная дата</td>
                            <td><?= (!empty($model->order_change_date) && ($model->order_change_date !== 0)) ? date('d-m-Y', $model->order_change_date) : "Не указано" ?></td>
                        </tr>
                        <tr>
                            <td>Дополнительная информация</td>
                            <td><?= !empty($model->comment) ? $model->comment : 'Не указано' ?></td>
                        </tr>
                        <tr>
                            <td colspan="2"><b>Информация о получателе</b></td>
                        </tr>
                        <tr>
                            <td>Имя</td>
                            <td><?= $model->user_name  . ' <br> (Код: ' . $model->user_id . ')'; ?></td>
                        </tr>

                        <tr>
                            <td>Email</td>
                            <td><?= $model->email; ?></td>
                        </tr>

                        <tr>
                            <td>Телефон</td>
                            <td><?= $model->user_phone; ?></td>
                        </tr>
                        <tr>
                            <td>Организация</td>
                            <?php if (!empty($model->organization_name)) : ?>
                                <td><?=  !empty($model->organization_name) ? $model->organization_name : 'Не указано' ?></td>
                            <?php else : ?>
                                <td><?= !empty($model->organization) ? $model->organization->name . ' - ' . $model->organization->inn . ' <br>(Код: ' . $model->organization->id . ')': "Не указано"?></td>
                            <?php endif; ?>
                        </tr>


                        <tr>
                            <td colspan="2"><b>Состояние заказа</b></td>
                        </tr>

                        <tr>
                            <td>Оплата</td>
                            <td><?= Order::getTypePaymentStatic($model->payment_type) ?></td>
                        </tr>
                        <tr>
                            <td>Статус</td>
                            <td data-order-exec="true">
                                <select name="" id="Order-order-status-<?= $model->id ?>">
                                    <?php

                                    foreach (Order::getListForSelectStatusOrder() as $k => $v) :
                                        $selected = '';
                                        if ($model->order_status == $k) {
                                            $selected = 'selected';
                                        }
                                    ?>
                                        <option value="<?= $k; ?>" <?= $selected; ?>><?= $v; ?></option>
                                    <?php endforeach; ?>
                                </select>

                                <button class="g-btn pull-right" data-action="<?= Url::to(['order/set-status']); ?>" data-key="<?= $model->id; ?>">Применить</button>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="box">
                <div class="box-header">

                </div>
                <div class="box-body">
                    <table class="table table-bordered table-striped kv-table-wrap">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Название</th>
                                <th>Артикул</th>
                                <th>Кол-во</th>
                                <th>Стоимость (₽)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $i = 1;
                            foreach ($model->orderGoods as $orderGood) :
                                $good = $orderGood->good;

                                $href = $good->link;

                            ?>
                                <?php if (!empty($orderGood->good)) : ?>
                                    <tr>
                                        <td><?= $i ?></td>
                                        <td><a href="<?= $href ?>" data-pjax="0" target="_blank"><?= $good->name ?></a></td>
                                        <td>
                                            <?= $good->code_t ?>
                                        </td>
                                        <td>
                                            <?= $orderGood->count ?>шт.
                                        </td>
                                        <td>
                                            <?= Yii::$app->myHelper->getForntPrice($orderGood->price * $orderGood->count); ?>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            <?php
                                $i++;
                            endforeach;
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>