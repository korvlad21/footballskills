<?php

use yii\helpers\Html;

$this->title = 'Добавление персонального предложения';
$this->params['breadcrumbs'][] = ['label' => 'Персональные предложения', 'url' => ['index']];

?>
<div class="article-create">
	<div class="row">
		<div class="col-sm-10">
			<div class="box">
				<div class="box-header with-border">
					<div style="float: left">
						<h3 class="box-title"><?= $this->title;?></h3>
					</div>
				</div>
				<div class="box-body">
					<?= $this->render('_form', ['model' => $model, 'seo' => $seo]) ?>
				</div>
			</div>
		</div>
	</div>
</div>
