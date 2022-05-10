<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\artist\Artist;

/* @var $this yii\web\View */
/* @var $model \common\models\admin\form\RegisterForm */

$this->title = Yii::t('app', 'Create new artist');

$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'Artist Manager'),
    'url' => ['/artist/index']
];
$this->params['breadcrumbs'][] = $this->title;
$js = "
$('form#{$model->formName()}').on('beforeSubmit', function(e, \$form) {
    var ajaxObj = Navar.ajaxLoadHtml('{$model->formName()}', 'update-container-add');
    ajaxObj.success(function(){
        Navar.reloadPjax('artistList-pjax');
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
                    <?= $form->field($model, 'key')->textInput(['dir' => 'ltr']) ?>
                    <?= $form->field($model, 'nameFa')->textInput() ?>
                    <?= $form->field($model, 'keyFa')->textInput() ?>
                    <?= $form->field($model, 'tag')->textarea(['dir' => 'ltr']) ?>
                    <?= $form->field($model, 'activity')->dropDownList(Artist::getTypeList()) ?>
                    <?= $form->field($model, 'like')->textInput(['dir' => 'ltr']) ?>
                    <?= $form->field($model, 'likeFa')->textInput(['dir' => 'ltr']) ?>
                    <?= $form->field($model, 'likeApp')->textInput(['dir' => 'ltr']) ?>
                    <?= $form->field($model, 'status')->dropDownList(Artist::getStatusList()) ?>
                    <?= $form->field($model, 'statusFa')->dropDownList(Artist::getStatusList()) ?>
                    <?= $form->field($model, 'statusApp')->dropDownList(Artist::getStatusList()) ?>
                    <?= $form->field($model, 'statusSite')->dropDownList(Artist::getStatusList()) ?>
                    <?= $form->field($model, 'image')->fileInput() ?>

                    <script>
                        document.getElementById('addform-name').onchange = function() {
                            document.getElementById('addform-key').value = document.getElementById('addform-name').value;
                        }
                        document.getElementById('addform-namefa').onchange = function() {
                            document.getElementById('addform-keyfa').value = document.getElementById('addform-namefa').value;
                        }
                    </script>

                    <div class="form-group">
                        <?= Html::submitButton(Yii::t('app', 'Create'), ['class' => 'btn btn-primary btn-block']) ?>
                    </div>
                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>