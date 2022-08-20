<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Users */

$this->title = 'Добавить пользователя';
$this->params['breadcrumbs'][] = ['label' => 'Администраторы', 'url' => ['index']];
?>
<div class="users-create">
    <?=
    $this->render('_form', [
        'model' => $model,
        'table' => $table,
        'tableName' => $tableName,
    ])
    ?>

</div>
