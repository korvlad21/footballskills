<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\widgets\ckeditor\CkeditorMy;
use kartik\date\DatePicker;
use kartik\datetime\DateTimePicker;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model app\models\Player */
/* @var $form yii\widgets\ActiveForm */

$modelName = "player";
$idForm = $model->isNewRecord ? 'form-player-create' : 'form-player-update';
?>

<div class="player-form">
    <?php $form = ActiveForm::begin(['id' => $idForm]); ?>
    <div class="tabs-block">
        <ul class="tabs-list clearfix">
            <li class="active">
                <a data-toggle="tab" href="#panel_player_1" tabname="panel_player_1">
                    <span>Основные параметры</span>
                </a>
            </li>
            <li class="">
                <a data-toggle="tab" href="#panel-characts" tabname="panel-characts">
                    <span>Характеристики</span>
                </a>
            </li>
        </ul>
        <div class="tab-content">
            <div id="panel_player_1" class="tab-pane fade in active">
                <div id="data-modal-player-form">
                    <div class="row">

                        <div class="col-sm-6">
                            <div class="col-sm-12">
                                <?= $form->field($model, 'surname')->textInput() ?>
                            </div>
                            <div class="col-sm-12">
                                <?= $form->field($model, 'name')->textInput() ?>
                            </div>
                            <div class="col-sm-12">
                                <?= $form->field($model, 'otchestvo')->textInput() ?>
                            </div>
                           
                        </div>

                        <div class="col-sm-6">
                            <div class="col-sm-12">
                                <div class="form-group field-player-birthday">
                                    <label class="control-label">Дата рождения</label>
                                    <?= DatePicker::widget([
                                        
                                        'model' => $model,
                                        'attribute' => 'birthday',
                                    ]) ?>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <?=$form->field($model, 'position')->dropDownList($model::NAME_POSITION)->label('Позиция')?>
                            </div>
                        </div>

                    </div>

                    

                </div>
            </div>
            <div id="panel-characts" class="tab-pane fade">
                <div class="row">
                    <div class="col-sm-12">
                            <?= $this->render('_characterictics', ['model' => $model]) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="form-group text-left">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-flat btn-info']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>