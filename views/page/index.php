<?php

use common\models\Page;
use kartik\editable\Editable;
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\PageSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Страницы';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="page-index">
  <div class="row">
    <div class="col-sm-8">
      <div class="box">
        <div class="box-header with-border">
          <div style="float: left">
            <h3 class="box-title"><?= $this->title ?></h3>
          </div>
          <div class="box-tools pull-right">
              <p><?= Html::a('Добавить страницу', ['create'], ['class' => 'btn btn-flat btn-info']) ?></p>
          </div>
        </div>
        <div class="box-body">
          <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'columns' => [
              ['class' => 'yii\grid\SerialColumn'],
              [
                'attribute' => 'name',
                'content' => function ($model) {
                    return '<a href="' . Url::to(['update', 'id' => $model->id]) . '">' . $model->name . '</a>';
                }
              ],
              [
                'attribute' => 'created_at',
                'format' => ['date', 'php:d.m.Y'],
              ],
              [
                'attribute' => 'updated_at',
                'format' => ['date', 'php:d.m.Y'],
              ],
              
              [
                'attribute' => 'visibility',
                'class' => '\kartik\grid\EditableColumn',
                'editableOptions' => [
                  'formOptions' => ['action' => ['/page/update-grid']],
                  'header' => 'видимость',
                  'inputType' => Editable::INPUT_CHECKBOX,
                  'options' => [
                    'class' => 'new_class',
                    'label' => 'Опубликован',
                  ],
                  'pjaxContainerId' => 'pjax-table',
                ],
                'content' => function ($model) {
                  return $model::getListYesNo($model->visibility);
                },
                'format' => 'boolean',
                'filter' => Page::getListYesNo(),
                'label' => 'Опубликована',
              ],

              [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update} &nbsp;  {delete}',

              ],
            ],
          ]);
          ?>
        </div>
      </div>
    </div>
  </div>
</div>