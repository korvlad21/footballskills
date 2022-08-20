<?php

use common\models\Category;
use yii\helpers\Url;


?>

<?php if (!empty($model->id)) : ?>
	<?php
	$idParent = $model->parentCategories[1]->id;

	$cats = Category::find()->all();

	$threeCats = Category::getArrayTree($cats, $idParent);


	?>
	<div class="tree-category">
		<?= ($idParent == $model->id) ? "<b>" . $model->name . "</b>" : '<a href="' . Url::to(['update', 'id' => $model->parentCategories[1]->id]) . '">' . $model->parentCategories[1]->name . '</a>' ?>
		<?php if (!empty($threeCats)) : ?>
			<?php foreach ($threeCats as $threeCat) : ?>

				<br>&nbsp;&nbsp; • &nbsp;
				<?= ($threeCat['id'] == $model->id) ? "<b>" . $threeCat['name'] . "</b>" : '<a href="' . Url::to(['update', 'id' => $threeCat['id']]) . '">' . $threeCat['name'] . '</a>' ?>

				<?php if (isset($threeCat['children']) && !empty($threeCat['children'])) : ?>
					<?php foreach ($threeCat['children'] as $subThreeCat) : ?>

						<br>&nbsp; &nbsp; &nbsp; &nbsp; - &nbsp;
						<?= ($subThreeCat['id'] == $model->id) ? "<b>" . $subThreeCat['name'] . "</b>" : '<a href="' . Url::to(['update', 'id' => $subThreeCat['id']]) . '">' . $subThreeCat['name'] . '</a>' ?>

					<?php endforeach; ?>
				<?php endif; ?>

			<?php endforeach; ?>
		<?php endif; ?>
	</div>
<?php endif; ?>