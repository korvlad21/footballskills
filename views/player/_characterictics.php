<?php

use app\models\Characteristic;
use kartik\select2\Select2;

$characts = $model->characts;
?>

<div class="row">
	<div class="col-sm-10 characts-good-block">
		<div class="col-sm-12">
			<h4 class="text-center"><b> Характеристики футболиста:</b></h4>
		</div>

		<div class="player-characteristics">
			<?php if (!empty($characts)) : ?>
				<?php foreach ($characts as $charact) : ?>
					<?= $this->render(
						'_charact_item',
						[
							'characteristic' => $charact->characteristic,
							'value' => $charact->value,
							'model' => $model,
						]
					) ?>
				<?php endforeach; ?>
			<?php endif; ?>
		</div>

		<div class="new-charact">
			<div class="col-sm-8">
				<?= Select2::widget([
					'name' => 'NewCharact[]',
					'data' => Characteristic::getListForSelectChild('name'),
					'options' => ['class' => 'new-charact-input', 'placeholder' => 'Добавить новую характеристику'],
					'pluginOptions' => [
						'tabindex' => false,
						'tags' => false,
					]
				]); ?>
			</div>
			<div class="col-sm-1">
				<button type="button" class="btn btn-primary new-charact-btn" data-model-id="<?= $model->id ?>">Создать</button>
			</div>
		</div>

	</div>
</div>