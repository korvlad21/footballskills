<?php

use common\models\CategoryCharacteristic;

?>

<div class="col-sm-12 charact-item">
	<div class="col-sm-11">
		<div class="form-group">
			<label class="control-label"><?= $name ?></label>
			<select class="form-control" name="CategoryFilter[<?= $characteristic_id ?>][type]">
				<option value="<?= CategoryCharacteristic::TYPE_DIAPAZON ?>" <?= ($typeSelect == CategoryCharacteristic::TYPE_DIAPAZON) ? "selected" : "" ?>>Диапазон</option>
				<option value="<?= CategoryCharacteristic::TYPE_SELECT ?>" <?= ($typeSelect == CategoryCharacteristic::TYPE_SELECT )  ? "selected" : ""  ?>>Селект</option>
			</select>
		</div>
	</div>
	<div class="col-sm-1 checkbox-filter-category">
		<div class="form-group">
			<input class="form-check-input" value="1" name="CategoryFilter[<?= $characteristic_id ?>][enable]" type="checkbox" <?= ($enable == 1) ? "checked" : "" ?>>
		</div>
	</div>
</div>