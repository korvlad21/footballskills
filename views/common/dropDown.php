<?php
    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\bootstrap\ActiveForm;
    
?>

<?php $form = ActiveForm::begin(['layout' => 'inline', 'action' => Url::to(['update-article-email', 'id' => $model->id]), 'options'  =>['data-updateform-table' => $model->id]]); ?>
<?= $form->field($model, 'email')->dropDownList(['Нет', 'Да'],['data-drop-down-form' => $model->id]) ?>
<?= Html::submitButton('<span class="glyphicon glyphicon-floppy-disk"></span>', ['class' => 'btn btn-info']) ?>
<?php ActiveForm::end(); ?>



