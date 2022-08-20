<?php

use common\models\City;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel common\UsersSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$user = Yii::$app->user->identity;

$this->title = 'Пользователи';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="users-index">
    <div class="row">
        <div class="col-sm-10">
            <?php Pjax::begin(); ?>
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Управление пользователями</h3>
                    <div class="pull-right box-tools">
                        <p><?= Html::a('Добавить', ['create'], ['class' => 'btn btn-flat btn-info']) ?></p>
                    </div>
                    <?php if (Yii::$app->user->id == 1) : ?>
                        <div class="callout">
                            <b>* Пользователя Админ Inrid запрещено редактировать и удалять. </b><br>
                            <span>Он виден только если под ним авторизоваться</span>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="box-body">
                    <?=
                    GridView::widget([
                        'dataProvider' => $dataProvider,
                        'filterModel' => $searchModel,

                        'columns' => [

                            [
                                'attribute' => 'id',
                                'label' => 'Id',
                                'contentOptions' => ['style' => 'width:60px;  min-width:60px;  '],
                            ],

                            [
                                'label' => 'Логин',
                                'attribute' => 'username',
                                'content' => function ($model) {
                                    return '<a href="' . Url::to(['update', 'id' => $model->id]) . '">' . $model->username . '</a>';
                                }
                            ],

                            'name',

                            'email:email',

                            [
                                'attribute' => 'category_id',
                                'label' => 'Город',
                                'format' => 'raw',
                                'value' => function ($model) {
                                    $str = !empty($model->city) ? $model->city->name : "";

                                    return  $str;
                                },

                                'filter' =>  Select2::widget([
                                    'model' => $searchModel,
                                    'attribute' => 'city_id',
                                    'data' => City::dropDown(),
                                    'value' => 'city_id',
                                    'options' => [
                                        'class' => 'form-control',
                                        'placeholder' => 'Выберите город'
                                    ],
                                    'pluginOptions' => [
                                        'allowClear' => true,
                                        'selectOnClose' => true,
                                    ]
                                ])

                            ],

                            [
                                'class' => 'yii\grid\ActionColumn',
                                'template' => ' {delete}',
                            ],
                        ],
                    ]);
                    ?>
                </div>
            </div>
        </div>
    </div>
    <?php Pjax::end(); ?>
</div>