<?php

use common\models\PersonalNew;
use kartik\editable\Editable;
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;

$this->registerJsFile(Yii::$app->request->baseUrl . '/js/dropDown/dropDown.js', ['depends' => backend\assets\AppAsset::class]);

$this->title = 'Персональные предложения';
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
            <?= Html::a('Добавить персональное предложение', ['create'], ['class' => 'btn btn-flat btn-info']); ?>
        </div>
      </div>
      <div class="box-body">
        <div id="articleTable">
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
              [
                'label' => 'Картинка',
                'format' => 'raw',
                'value' => function ($model) {
                  return Html::img(Url::toRoute($model->getImage()->getPath('x150')), [
                    'alt' => 'yii2 - картинка в gridview',
                    'style' => 'width:150px;'
                  ]);
                },
              ],

              [
                'attribute' => 'date',
                'format' => ['date', 'php:d.m.Y'],
              ],

              [
                'attribute' => 'visibility',
                'class' => '\kartik\grid\EditableColumn',
                'editableOptions' => [
                  'formOptions' => ['action' => ['/personal-new/update-grid']],
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
                'filter' => PersonalNew::getListYesNo(),
                'label' => 'Опубликована',
              ],
                [
                    'label' => 'Пользователи',
                    'attribute' => 'user_fio',
                    'content' => function ($model) {
                        $modelUsers=$model->personalNewUser;
                        $users='';
                        foreach ($modelUsers as $modelUser) {
                            $users.='<a href="' . Url::to([
                                    '/user-manage', "UsersSearch[email]" => $modelUser->user->email
                                ]) . '">'.$modelUser->user->email.'</a><br>';
                        }

                        return $users;
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