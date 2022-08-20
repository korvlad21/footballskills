<?php

use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>

<div class="users-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-sm-12">
            <div class="box box-info shadow">
                <div class="box-header with-border">
                    <h3 class="box-title">Основные данные</h3>
                </div>
                <div class="box-body">
                    <div class="row">

                        <div class="col-sm-4">
                            <?= $form->field($model, 'username')->textInput(['maxlength' => true, 'placeholder' => 'Username']) ?>
                            <?= $form->field($model, 'password_hash')->textInput(['maxlength' => true, 'placeholder' => 'Password'])->label('Пароль') ?>
                            <?= $form->field($model, 'name')->textInput(['maxlength' => true,]) ?>
                            <?= $form->field($model, 'email')->textInput(['maxlength' => true,]) ?>
                            <?=
                            $form->field($model, 'city_id')->widget(Select2::class, [
                                'data' => \common\models\City::getListForSelect('name'),
                                'options' => ['placeholder' => 'Выберите город'],
                                'pluginOptions' => [
                                    'tabindex' => false,
                                    'tags' => false,
                                    'tokenSeparators' => [',', ' '],
                                ],
                            ])->label('Город');
                            ?>
                            <?= $form->field($model, 'status_icms')->dropDownList([
                                $model::ICMS_MANAGER => 'Менеджер',
                                $model::ICMS_ADMIN => 'Администратор',
                            ])->label('Статус');
                            ?>
                        </div>

                        <div class="col-sm-4">
                            <?= $form->field($model, 'contact_phone_1')->textInput([]) ?>
                            <?= $form->field($model, 'contact_phone_2')->textInput([]) ?>
                            <?= $form->field($model, 'contact_email')->textInput([]) ?>
                            <?= $form->field($model, 'contact_telegram')->textInput([]) ?>
                            <?= $form->field($model, 'contact_whatsapp')->textInput([]) ?>
                            <?= $form->field($model, 'contact_icq')->textInput() ?>
                        </div>

                        <div class="col-sm-4">
                            <?= $form->field($model, 'image')->fileInput(['accept' => "image/jpeg, image/png"])->label('Фотография') ?>
                            <?php
                            $images[] = $model->getImage();
                            if (!empty($images)) {
                                print $this->render('../common/_view_images', compact('images', 'model'));
                            }
                            ?>
                        </div>

                    </div>
                </div>
                <div class="box-footer">
                    <div class="form-group">
                        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-flat btn-info']) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>