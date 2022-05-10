<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $updateModel \backend\controllers\admin\ChangePassword */

$this->title = Yii::t('app', 'Change password');

$this->params['breadcrumbs'][] = $this->title;

$js = "
$('form#{$updateModel->formName()}').on('beforeSubmit', function(e, \$form) {
    Navar.ajaxLoadHtml('{$updateModel->formName()}', 'update-container-password');
    return false;
}).on('submit', function(e){
    e.preventDefault();
});
";
$this->registerJs($js);
?>
<div id="update-container-password">
    <div class="row">
        <div class="col-md-4">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title"><?= $this->title; ?></h3>
                </div>
                <div class="panel-body">
                    <?= common\widgets\Alert::widget(); ?>
                    <?php
                    $form = ActiveForm::begin([
                        'id' => $updateModel->formName(),
                        'enableClientValidation' => true,
                    ]); ?>
                    <?= $form->field($updateModel, 'old_password')->passwordInput(['dir' => 'ltr']) ?>
                    <?= $form->field($updateModel, 'password')->passwordInput(['dir' => 'ltr']) ?>
                    <?= $form->field($updateModel, 'confirmPassword')->passwordInput(['dir' => 'ltr']) ?>
                    <div class="form-group">
                        <?= Html::submitButton(Yii::t('app', 'Change'), ['class' => 'btn btn-primary btn-block']) ?>
                    </div>
                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>