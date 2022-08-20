<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

$this->title = 'Импорт/Экспорт';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="page-form">
  <h1><?= Html::encode($this->title) ?></h1>
  <?php $form = ActiveForm::begin(); ?>
    <div class="tabs-block">
      <br/>
      <div class="row">
        <div class="col-sm-1">
          <?= Html::submitButton('Импорт', ['class' => 'btn btn-info']) ?>
        </div>
        <div class="col-sm-1">
          <input type="file">
        </div>
      </div>
      <br />
      <div class="row">
        <div class="col-sm-1">
          <a href="<?= Url::to(['/site/export'])?>" class="btn btn-info" data-export="true">Экспорт</a>
        </div>
      </div>
    </div>
    </div>
  <?php ActiveForm::end(); ?>
</div>
