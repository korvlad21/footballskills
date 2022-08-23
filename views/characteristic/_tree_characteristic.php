<?php

use app\models\Characteristic;
use yii\helpers\ArrayHelper;


?>

<?php

$cats = Characteristic::find()->all();
$data = ArrayHelper::toArray($cats, [
	'app\models\Characteristic' => [
		'id',
		'name',
		'parent_id',
	],
]);
$tree = Characteristic::form_tree($data);

$build = Characteristic::build_tree($tree, null, $model->id);
?>
<div class="tree-characteristic">
	<?= $build ?>

</div>