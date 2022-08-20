<?php

use yii\helpers\Html;

$this->title = 'Редактирование TDK: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'SEO', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name];

?>
<div class="seo-update">
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
