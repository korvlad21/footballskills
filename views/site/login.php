<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;

$this->title = 'Авторизация';

$fieldOptions1 = [
    'options' => ['class' => 'form-group has-feedback field-loginform-username'],
    'inputTemplate' => "{input}<span class='glyphicon glyphicon-envelope form-control-feedback'></span>"
];
$fieldOptions2 = [
    'options' => ['class' => 'form-group has-feedback field-loginform-password'],
    'inputTemplate' => "{input}<span class='glyphicon glyphicon-lock form-control-feedback'></span>"
];
?>

<body class="login-body">
  <div class="login-box">
    <div class="login-logo">
        <a href="#"><b>Авторизация</b></a>
    </div>
    <div class="login-box-body">
<?php $form = ActiveForm::begin(['id' => 'login-form', 'enableClientValidation' => false]); ?>
        <div class="form-group has-feedback  required">
            <?= $form
            ->field($model, 'username', $fieldOptions1)
            ->label(false)
            ->textInput() ?>
        </div>
        <div class="form-group has-feedback field-loginform-password required">
            <?= $form
            ->field($model, 'password', $fieldOptions2)
            ->label(false)
            ->passwordInput() ?>
        </div>
        <div class="row">
            <div class="text-right">
                <?= Html::submitButton('Войти', ['class' => 'btn-krasnodar', 'name' => 'login-button']) ?>
            </div>
        </div>
<?php ActiveForm::end(); ?>
    </div>
</div>