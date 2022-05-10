<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model \common\models\admin\form\RegisterForm */

$this->title = Yii::t('app', 'Zip album');

$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'Zip Manager'),
    'url' => ['/music/zip']
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
                    ]); ?>

                    <?= $form->field($model, 'id')->textInput() ?>

                    <div class="form-group">
                        <?= Html::submitButton(Yii::t('app', 'Create'), ['class' => 'btn btn-primary btn-block']) ?>
                    </div>
                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>