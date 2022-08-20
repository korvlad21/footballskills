<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;

$this->title = 'Error 404';
?>
	<div class="row">
		<div class="col-sm-5">
		</div>
			<div class="col-sm-2">
			<div class="site-error">
				<br>
				<br>
				<h1><?= Html::encode($this->title) ?></h1>
				<p>Запрашиваемая страница не существует</p>
			</div>  
			<div class="text-center">
				<a href="/icms" class="btn-intrid">на главную</a>
			</div>
		</div>
		<div class="col-sm-5">
		</div>
	</div>