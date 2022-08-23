<?php

use yii\helpers\Html;

$this->title = 'Обновление футболиста ' . $model->surname.' '.substr($model->name, 0, 2).'. '.substr($model->otchestvo, 0, 2).'.';
$this->params['breadcrumbs'][] = ['label' => 'Футболисты', 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->surname.' '.substr($model->name, 0, 2).'. '.substr($model->otchestvo, 0, 2).'.';
?>
<div class="player-update">
	<div class="row">
		<div class="col-sm-10">
			<div class="box">
				<div class="box-header with-border">
					<div style="float: left">
						<h3 class="box-title"><?= $this->title;?></h3>
					</div>
				</div>
				<div class="box-body">
				<?= $this->render('_form', ['model' => $model]) ?>
				</div>
			</div>
		</div>
	</div>
</div>
