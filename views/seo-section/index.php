<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

$this->title = 'SEO';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
  <div class="col-sm-6">
    <div class="box">
      <div class="box-header with-border">
        <div style="float: left">
          <h3 class="box-title"><?= $this->title?></h3>
        </div>
        <div class="box-tools pull-right">
          <?php if(Yii::$app->user->can('createSeo')):?>
            <p><?= Html::a('Добавить', ['create'], ['class' => 'btn btn-flat btn-info']) ?></p>
          <?php endif;?>   
        </div>
      </div>
      <div class="box-body">
        <?= GridView::widget([
          'dataProvider' => $dataProvider,
          // 'filterModel' => $searchModel,
          'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
              'attribute' => 'name',
              'content' => function ($model) {
                if (Yii::$app->user->can('updateSeo')){
                  return '<a href="' . Url::to(['update', 'id' => $model->id]) . '">' . $model->name . '</a>';
                }else{
                  return $model->name;
                }
              }
            ],
            'slug',
            [
             'class' => 'yii\grid\ActionColumn',
             'template' => '{delete}',
             'visibleButtons' => [
              'delete' => Yii::$app->user->can('deleteSeo'),
            ]
          ],
        ],
      ]); ?>
    </div>
  </div>
</div>
</div>
