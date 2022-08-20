<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Characteristic */

$this->title = Yii::t('app', 'Создание категории характеристик');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Категории характеристик'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="characteristic-create">
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