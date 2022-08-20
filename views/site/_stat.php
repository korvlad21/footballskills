<?php 
  
  use yii\helpers\Html; 
  use backend\assets\CharsAsset;

CharsAsset::register($this);

?>

<div class="box">
  <div class="box-header with-border">
    <h3 class="box-title">Отчет за неделю</h3>
  </div>
  <div class="box-body">
      <p class="text-center">с <?= date("d.m.Y", mktime(0, 0, 0, date('m'), date('d') - 7, date('Y')))?> по <?= date("d.m.Y") ;?></p>
      <div class="chart">
          <div class="row">
            <div class="col-md-12">
              <div class="col-xs-6">
                <canvas id="myChart"></canvas>
              </div>
              <div class="col-xs-6">
                <canvas id="myLineChart"></canvas>
              </div>
            </div>
          </div>
      </div>
  </div>
  <div class="box-footer">
    <div class="row">
      <div class="col-md-12">
        <div class="col-sm-3">
          <div class="description-block">
            <span class="description-percentage text-green"><i class="fa fa-caret-up"></i> 17%</span>
            <h5 class="description-header">552 500 ₽</h5>
          </div>
        </div>
        <div class="col-sm-3">
          <div class="description-block">
            <span class="description-percentage text-yellow"><i class="fa fa-caret-left"></i> 0%</span>
            <h5 class="description-header">2 500 шт.</h5>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
