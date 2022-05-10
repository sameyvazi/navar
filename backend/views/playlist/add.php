<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\playlist\Playlist;

/* @var $this yii\web\View */
/* @var $model \common\models\admin\form\RegisterForm */

$this->title = Yii::t('app', 'Create new playlist');

$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'Playlist Manager'),
    'url' => ['/playlist/index']
];
$this->params['breadcrumbs'][] = $this->title;
$js = "
$('form#{$model->formName()}').on('beforeSubmit', function(e, \$form) {
    var ajaxObj = Navar.ajaxLoadHtml('{$model->formName()}', 'update-container-add');
    ajaxObj.success(function(){
        Navar.reloadPjax('playlistList-pjax');
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
                    <?= $form->field($model, 'mood_id')->widget(\yii\jui\AutoComplete::classname(),[

                        'clientOptions' => [
                            'source' => $mood,
                            'height' => 120,
                            'autoFill'=>true,
                            'minLength'=>'2',
                            'select' => new \yii\web\JsExpression("function( event, ui ) {
                                $('#mood_id').val(ui.item.id);
                            }")
                        ],

                    ], ['width' => '300']) ?>

                    <?= $form->field($model, 'no')->dropDownList([Playlist::getNumberList()]) ?>
                    <?= $form->field($model, 'limit')->dropDownList([Playlist::getNumberListRound()]) ?>
                    <?= $form->field($model, 'status')->dropDownList(Playlist::getStatusList()) ?>
                    <?= $form->field($model, 'status_fa')->dropDownList(Playlist::getStatusList()) ?>
                    <?= $form->field($model, 'status_app')->dropDownList(Playlist::getStatusList()) ?>
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