<?php

use yii\helpers\Html;


$this->title = Yii::t('app', 'Добавить характеристику');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Характеристики'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="characteristic-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
