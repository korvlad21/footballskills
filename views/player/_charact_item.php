<div class="col-sm-6 charact-item" data-characteristic-item-id="<?= $characteristic->id ?>">
	<div class="col-sm-9">
		<div class="form-group">
			<label class="control-label" for="player-charact-value"><?= $characteristic->name ?></label>
			<input type="number" id="player-charact-value<?= $characteristic->id ?>" class="form-control" name="PlayerCharact[<?= $characteristic->id ?>][value]" value="<?= $value ?>">
			<div class="help-block"></div>
		</div>
	</div>
	<div class="col-sm-1">
		<button type="button" class="btn btn-danger delete-charact-btn <?= isset($class) ? $class : '' ?>" data-model-id="<?= $model->id ?>" data-characteristic-id="<?= $characteristic->id ?>">X</button>
	</div>
</div>