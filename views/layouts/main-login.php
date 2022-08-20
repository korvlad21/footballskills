<?php
/* @var $this \yii\web\View */
/* @var $content string */

use app\assets\AppAsset;
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;
use common\widgets\Alert;
use yii\helpers\Url;

AppAsset::register($this);

?>

<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
 	<meta charset="<?= Yii::$app->charset ?>">
 	<meta http-equiv="X-UA-Compatible" content="IE=edge">
 	<meta name="viewport" content="width=device-width, initial-scale=1">
 	<?php $this->registerCsrfMetaTags() ?>
 	<title><?= Html::encode($this->title) ?></title>
 	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
 	<?php $this->head() ?>
</head>
<?php $this->beginBody() ?>
	<?= $content?>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
