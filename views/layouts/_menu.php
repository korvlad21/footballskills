<?php

use app\components\widgets\Menu;
use app\models\User;

$user = Yii::$app->user->identity;

?>
<aside class="main-sidebar">
    <section class="sidebar">
        <ul class="sidebar-menu tree" data-widget="tree">
            <li class="header">Оценки футболиста</li>
        </ul>

            <?=
            Menu::widget(
                [
                    'options' => ['class' => 'sidebar-menu tree', 'data-widget' => 'tree'],
                    'items' => [
                        ['label' => 'Футболисты', 'icon' => 'user', 'url' => ['/player']],
                        ['label' => 'Категории характеристик', 'icon' => 'inbox', 'url' => ['/characteristic']],
                        ['label' => 'Характеристики футболистов', 'icon' => 'line-chart', 'url' => ['/characteristic-player']],
                       
                    ],
                ]
            )
            ?>

    </section>
    <img src="/img/emblema_fk_krasnodar.png" alt="ФК Краснодар" class="krasnodar-logo">
</aside>