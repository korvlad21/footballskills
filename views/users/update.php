<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Users */

$this->title = 'Редактировать пользователя: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Пользователи', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->username];
?>
<div class="users-update">

    <?= $this->render('_form', [
        'model' => $model,
        'table' => $table,
        'tableName' => $tableName,
    ]) ?>

</div>
