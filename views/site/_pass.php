<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;

$change_pass_form = new \backend\models\ChangePasswordForm();

$fieldOptions2 = [
    'options' => ['class' => 'form-group has-feedback field-loginform-password'],
    'inputTemplate' => "{input}<span class='glyphicon glyphicon-lock form-control-feedback'></span>"
];
?>
<br/>
<?php $form = ActiveForm::begin(['action' => ['site/change-admin-pass']]); ?>
<div class="row">
<div class="col-sm-6">
<div class="tabs-block">
    <ul class="tabs-list clearfix">
        <li class="active">
            <a data-toggle="tab" href="#panel_pass">
                <span>Изменить пароль для входа</span>
            </a>
        </li>
    </ul>
    
    <div class="tab-content">
        <div id="panel_pass" class="tab-pane fade in active">
            <div id="data-modal-good">
                <div class="row">
                    <div class="col-sm-6">
                        <?= $form->field($change_pass_form, 'new_password', $fieldOptions2)->passwordInput(['placeholder' => $change_pass_form->getAttributeLabel('Password')]) ?>
                    </div>
                    <div class="col-sm-6">
                        <?= $form->field($change_pass_form, 'repeat_new_password', $fieldOptions2)->passwordInput(['placeholder' => $change_pass_form->getAttributeLabel('Password')]) ?>
                    </div>
                </div>
            </div>
        </div> 
    </div>
    <div class="form-group text-right">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-info']) ?>
    </div>
</div>
</div>

<?php ActiveForm::end(); ?>
