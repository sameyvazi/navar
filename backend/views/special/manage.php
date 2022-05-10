<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\artist\Artist;
use yii\web\JsExpression;
use common\models\special\Special;

/* @var $this yii\web\View */
/* @var $model backend\models\special\UpdateForm */

$this->title = Yii::t('app', 'Update special');

$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'Special Manager'),
    'url' => ['/special/index']
];
$this->params['breadcrumbs'][] = $this->title;

//$js = "
//$('form#{$model->formName()}').on('beforeSubmit', function(e, \$form) {
//    var ajaxObj = Navar.ajaxLoadHtml('{$model->formName()}', 'update-container-add');
//    ajaxObj.success(function(){
//        Navar.reloadPjax('artistList-pjax');
//        $.fancybox.close();
//    });
//    return false;
//}).on('submit', function(e){
//    e.preventDefault();
//});
//";
//$this->registerJs($js);
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
                        'id' => $model->formName(),
                        'enableClientValidation' => true,
                    ]); ?>

                    <?= $form->field($model, 'music_id')->widget(\yii\jui\AutoComplete::classname(),[

                        'clientOptions' => [
                            'source' => $music,
                            'height' => 120,
                            'autoFill'=>true,
                            'minLength'=>'2',
                            'select' => new JsExpression("function( event, ui ) {
                                $('#music').val(ui.item.id);
                            }")
                        ],

                    ], ['width' => '300']) ?>

                    <?= $form->field($model, 'playlist_id')->widget(\yii\jui\AutoComplete::classname(),[

                        'clientOptions' => [
                            'source' => $playlist,
                            'height' => 120,
                            'autoFill'=>true,
                            'minLength'=>'2',
                            'select' => new JsExpression("function( event, ui ) {
                                $('#music').val(ui.item.id);
                            }")
                        ],

                    ], ['width' => '300']) ?>

                    <?= $form->field($model, 'artist_id')->widget(\yii\jui\AutoComplete::classname(),[

                        'clientOptions' => [
                            'source' => $artist,
                            'height' => 120,
                            'autoFill'=>true,
                            'minLength'=>'2',
                            'select' => new JsExpression("function( event, ui ) {
                                $('#music').val(ui.item.id);
                            }")
                        ],

                    ], ['width' => '300']) ?>

                    <?= $form->field($model, 'type')->dropDownList(Special::getTypeList()) ?>
                    <?= $form->field($model, 'position')->dropDownList(Special::getPositionList()) ?>
                    <?= $form->field($model, 'no')->dropDownList(Special::getNumberList()) ?>

                    <div class="form-group">
                        <?= Html::submitButton(Yii::t('app', 'Update'), ['class' => 'btn btn-primary btn-block']) ?>
                    </div>
                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>