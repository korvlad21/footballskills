<?php

use common\models\Firm;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use frontend\models\User;

?>

<div class="userdata-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="tabs-block">
        <ul class="tabs-list clearfix">
            <li class="active">
                <a data-toggle="tab" href="#panel_userdata_1">
                    <span>Основные параметры</span>
                </a>
            </li>
        </ul>
        <div class="tab-content">
            <div id="panel_userdata_1" class="tab-pane fade in active">
                <div id="data-modal-userdata-form">

                    <div class="row">
                        <div class="col-sm-6">
                            <?= $form->field($model, 'name')->textInput([]) ?>
                            <?= $form->field($model, 'email')->textInput([]) ?>
                            <?= $form->field($model, 'phone')->widget(\yii\widgets\MaskedInput::className(), [
                                'mask' => '+7 (999) 999-9999'
                            ]) ?>
                            <?= $form->field($model, 'passForSet')->textInput([]) ?>
                            <?= $form->field($model, 'repeatPassForSet')->textInput([]) ?>
                        </div>
                        <div class="col-sm-6">
                            <?= $form->field($model, 'vendor_code')->textInput(['readonly' => true]) ?>
                            <?= $form->field($model, 'vendor_rule')->textInput(['readonly' => true]) ?>
                            <?= $form->field($model, 'address')->textInput([]) ?>
                            <?php if(Yii::$app->user->identity->isIcmsAdmin):?>
                            <?=
                                $form->field($model, 'manager_id')->widget(Select2::class, [
                                    'data' => User::getListForSelectManager('email'),
                                    'options' => ['placeholder' => 'Выберите менеджера'],
                                    'pluginOptions' => [
                                        'tabindex' => false,
                                        'tags' => false,
                                        'tokenSeparators' => [',', ' '],
                                    ],
                                ])->label('Менеджер');
                           

                                ?>
                            <?php endif;?>
                            <?=$form->field($model, 'firmsArray')->widget(Select2::class, [
                            'data' => Firm::getListForSelectFirm('name'),
                            'options' => ['placeholder' => 'Выберите фирмы', 'multiple' => true],
                            'pluginOptions' => [
                            'allowClear' => true,
                            'tabindex' => false,
                            'tags' => false,
                            'tokenSeparators' => [',', ' '],
                            ],
                            ])->label('Фирмы');
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="form-group text-right">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-info']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>