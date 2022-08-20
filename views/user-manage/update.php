<?php

use yii\helpers\Html;

/* @var $this yii\web\View */

$this->title = 'Редактирование пользователя';
$this->params['breadcrumbs'][] = ['label' => 'Пользователи', 'url' => ['index']];
?>
<div class="user-data-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
