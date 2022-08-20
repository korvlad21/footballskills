<?php

use yii\helpers\Html;

$this->title = 'Редактирование: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Разделы новостей', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name];
?>

<div class="ge-create">
    <div class="row">
		<div class="col-sm-6">
			<div class="box">
				<div class="box-header with-border">
					<div style="float: left">
						<h3 class="box-title"><?= Html::encode($this->title) ?></h3>
					</div>
				</div>
				<div class="box-body">
					<?= $this->render('_form', ['model' => $model, 'seo' => $seo]) ?>
				</div>
			</div>
		</div>
	</div>
</div>