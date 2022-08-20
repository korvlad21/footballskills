<?php

use common\models\Units;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>

<div class="portfolio-form">

    <?php $form = ActiveForm::begin(['action' => '/icms/characteristic/create']); ?>

    <div class="tabs-block">

        <div class="tab-content">
            <div id="panel-portfolio-1" class="tab-pane fade in active">
                <div id="data-modal-portfolio-main">

                    <div class="row">
                        <div class="col-sm-12">
                            <?= $form->field($model, 'name')->textInput() ?>
                            <?=
                            $form->field($model, 'unitsArray')->widget(Select2::class, [
                                'data' => Units::getListForSelect(),
                                'options' => ['placeholder' => 'Выберите единицы измерения', 'multiple' => true],
                                'pluginOptions' => [
                                    'allowClear' => true,
                                    'tabindex' => false,
                                    'tags' => false,
                                    'tokenSeparators' => [',', ' '],
                                ],
                            ])->label('Единицы измерения');
                            ?>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
    <div class="form-group text-right">
        <?= Html::submitButton('Добавить', ['class' => 'btn btn-info']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>