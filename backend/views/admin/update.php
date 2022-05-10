<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $updateModel backend\models\admin\UpdateForm */
/* @var $admin common\models\admin\Admin */

$this->title = Yii::t('app', 'Update information {username}', [
    'username' => Html::encode($admin->username)
]);
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'Admins Manager'),
    'url' => ['/admin/index']
];
$this->params['breadcrumbs'][] = Html::encode($admin->username);

$js = "
$('form#{$updateModel->formName()}').on('beforeSubmit', function(e, \$form) {
    var ajaxObj = Navar.ajaxLoadHtml('{$updateModel->formName()}', 'update-container');
    ajaxObj.success(function(){
        Navar.reloadPjax('adminList-pjax');
    });
    return false;
}).on('submit', function(e){
    e.preventDefault();
});
";
$this->registerJs($js);
?>
<div id="update-container" class="max-width-500">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title"><?= Yii::t('app', 'Update information') ?></h3>
                </div>
                <div class="panel-body">
                    <?= common\widgets\Alert::widget(); ?>
                    <?php
                    $form = ActiveForm::begin([
                        'id' => $updateModel->formName(),
                        'enableClientValidation' => true,
                    ]); ?>
                    <?= $form->field($updateModel, 'password')->passwordInput(['dir' => 'ltr']) ?>
                    <div class="form-group">
                        <?= Html::submitButton(Yii::t('app', 'Update'), ['class' => 'btn btn-primary btn-block']) ?>
                    </div>
                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>