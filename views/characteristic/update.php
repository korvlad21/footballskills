<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Characteristic */

$this->title =  'Редактирование характеристик: '. $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Категории характеристик', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['update', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Редактирование';
?>
<div class="characteristic-update">
    <div class="row">
        <div class="col-sm-10">
            <div class="box">
                <div class="box-header with-border">
                    <div style="float: left">
                        <h3 class="box-title"><?= $this->title; ?></h3>
                    </div>
                </div>
                <div class="box-body">

                    <?= $this->render('_form', [
                        'model' => $model,
                    ]) ?>
                </div>
            </div>
        </div>
    </div>
</div>