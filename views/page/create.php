<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Page */

$this->title = 'Добавление страницы';
$this->params['breadcrumbs'][] = ['label' => 'Страницы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="page-create">
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
