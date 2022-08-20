<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Good */

$this->title = Yii::t('app', 'Создание товара');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Товары'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="good-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
