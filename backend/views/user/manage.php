<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\user\UpdateForm */

$this->title = Yii::t('app', 'Update user');

$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'User Manager'),
    'url' => ['/user/index']
];
$this->params['breadcrumbs'][] = $this->title;

$js = "
$('form#{$model->formName()}').on('beforeSubmit', function(e, \$form) {
    var ajaxObj = Navar.ajaxLoadHtml('{$model->formName()}', 'update-container-add');
    ajaxObj.success(function(){
        Navar.reloadPjax('userList-pjax');
        $.fancybox.close();
    });
    return false;
}).on('submit', function(e){
    e.preventDefault();
});
";
$this->registerJs($js);
?>
<div id="update-container-add" class="max-width-500">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title"><?= $this->title; ?></h3>
                </div>
                <div class="panel-body">
                    <?= common\widgets\Alert::widget(); ?>
                    <?php
                    $form = ActiveForm::begin([
                        'id' => $model->formName(),
                        'enableClientValidation' => true,
                    ]); ?>
                    <?= $form->field($model, 'name')->textInput() ?>
                    <?= $form->field($model, 'lastname')->textInput() ?>
                    <?= $form->field($model, 'gender')->dropDownList([
                        '1' => Yii::t('app', 'Man'),
                        '0' => Yii::t('app', 'Woman')
                    ]); ?>
                    <div class="form-group">
                        <?= Html::submitButton(Yii::t('app', 'Update'), ['class' => 'btn btn-primary btn-block']) ?>
                    </div>
                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>