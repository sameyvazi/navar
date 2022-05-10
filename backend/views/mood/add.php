<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\mood\Mood;

/* @var $this yii\web\View */
/* @var $model \common\models\admin\form\RegisterForm */

$this->title = Yii::t('app', 'Create new mood');

$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'Mood Manager'),
    'url' => ['/mood/index']
];
$this->params['breadcrumbs'][] = $this->title;
$js = "
$('form#{$model->formName()}').on('beforeSubmit', function(e, \$form) {
    var ajaxObj = Navar.ajaxLoadHtml('{$model->formName()}', 'update-container-add');
    ajaxObj.success(function(){
        Navar.reloadPjax('moodList-pjax');
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
        <div class="col-md-6">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title"><?= $this->title; ?></h3>
                </div>
                <div class="panel-body">
                    <?= common\widgets\Alert::widget(); ?>
                    <?php
                    $form = ActiveForm::begin([
//                        'id' => $model->formName(),
//                        'enableClientValidation' => true,
                        'options' => ['enctype' => 'multipart/form-data']
                    ]); ?>

                    <?= $form->field($model, 'name')->textInput(['dir' => 'ltr']) ?>
                    <?= $form->field($model, 'name_fa')->textInput(['dir' => 'ltr']) ?>
                    <?= $form->field($model, 'no')->dropDownList([Mood::getNumberList()]) ?>
                    <?= $form->field($model, 'status')->dropDownList(Mood::getStatusList()) ?>
                    <?= $form->field($model, 'status_fa')->dropDownList(Mood::getStatusList()) ?>
                    <?= $form->field($model, 'status_app')->dropDownList(Mood::getStatusList()) ?>
                    <?= $form->field($model, 'image')->fileInput() ?>

                    <div class="form-group">
                        <?= Html::submitButton(Yii::t('app', 'Create'), ['class' => 'btn btn-primary btn-block']) ?>
                    </div>
                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>