<?php

use yii\helpers\Url;
use app\models\Category;
use yii\helpers\ArrayHelper;


?>

<?php

$cats = Category::find()->all();
$data = ArrayHelper::toArray($cats, [
	'app\models\Category' => [
		'id',
		'name',
		'parent_id',
	],
]);
$tree = Category::form_tree($data);
$build = Category::build_tree($tree, null, $model->id);

?>
<div class="tree-category">
	<?= $build ?>

</div>