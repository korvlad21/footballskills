<?php

use yii\helpers\Url;
use yii\helpers\Html;
use app\models\Player;
use kartik\grid\GridView;
use kartik\editable\Editable;
use kartik\daterange\DateRangePicker;

$this->registerJsFile(Yii::$app->request->baseUrl . '/js/dropDown/dropDown.js', ['depends' => app\assets\AppAsset::class]);

$this->title = 'Футболисты';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
  <div class="col-sm-10">
    <div class="box">
      <div class="box-header with-border">
        <div style="float: left">
          <h3 class="box-title"><?= $this->title; ?></h3>
        </div>
        <div class="pull-right btn-group">
            <?= Html::a('Добавить футболиста', ['create'], ['class' => 'btn btn-flat btn-info']); ?>
        </div>
      </div>
      <div class="box-body">
        <div id="playerTable" >
          <?=
          GridView::widget([
            'dataProvider' => $dataProvider,
             'filterModel' => $searchModel,
            'options' => [
              'style' => 'word-wrap: break-word;'
            ],
            'columns' => [
              'id',
              'surname',
              'name',
              'otchestvo',
              [
                'attribute' => 'position',
                'label' => 'Позиция',
    
                'value' => function ($model) {
                    return $model->getPositionName();
                },
                 'filter' => Player::NAME_POSITION
              ],
            
            [
              'attribute' => 'birthday',
              'value' => function ($model) {
                  return date('d.m.Y', strtotime($model->birthday));
              },
              'filter' => kartik\daterange\DateRangePicker::widget([
                  'model' => $searchModel,
                  'attribute' => 'birthday',
                  'convertFormat' => true,
                  'useWithAddon' => true,
                  'language' => 'ru',
                  'hideInput' => true,
                  'startAttribute' => 'birthday_start',
                  'endAttribute' => 'birthday_end',
                  'pluginOptions' => [
                      'locale' => ['format' => 'Y-m-d', 'cancelLabel' => 'Очистить'], // from demo config
                      'separator' => '-',
                      'opens' => 'left',
                      'showDropdowns' => true
                  ],
                  'pluginEvents' => [
                      "cancel.daterangepicker" => "function(ev, picker) {
                          $('#playerssearch-birthday').val('').trigger('change');
                      }",
                  ],
              ]),
          ],

              [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update} &nbsp; {delete}',
              ],
            ],
          ]);
          ?>
        </div>
      </div>
    </div>
  </div>
</div>