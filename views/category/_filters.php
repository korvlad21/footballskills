<div class="row">
	<div class="col-sm-10 filter-category-block">
		<div class="col-sm-12">
			<h4 class="text-center"><b> Фильтрация категории:</b></h4>
		</div>

		<div class="category-filters">
			<?php if (!empty($model->filtersForUpdate)) : ?>
				<?php foreach ($model->filtersForUpdate as $characteristic_id => $filterUpdate) : ?>
					<?= $this->render(
						'_filter_item',
						[
							'characteristic_id' => $characteristic_id,
							'name' => $filterUpdate['name'],
							'enable' => $filterUpdate['enable'],
							'typeSelect' => $filterUpdate['type'],
						]
					) ?>
				<?php endforeach; ?>
			<?php else : ?>
				<h5 class="text-center">Ничего не найдено</h5>
			<?php endif; ?>
		</div>

	</div>
</div>