<div class="col-sm-12 charact-item" data-characteristic-item-id="<?= $characteristic->id ?>">
	<div class="col-sm-9">
		<div class="form-group">
			<label class="control-label" for="good-charact-value"><?= $characteristic->name ?></label>
			<input type="text" id="good-charact-value" class="form-control" name="GoodCharact[<?= $characteristic->id ?>][value]" value="<?= $value ?>">
			<div class="help-block"></div>
		</div>
	</div>
	<div class="col-sm-1">
		<button type="button" class="btn btn-danger delete-charact-btn <?= isset($class) ? $class : '' ?>" data-model-id="<?= $model->id ?>" data-characteristic-id="<?= $characteristic->id ?>">X</button>
	</div>
</div>