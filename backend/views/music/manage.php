<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\music\Music;
use yii\web\JsExpression;

/* @var $this yii\web\View */
/* @var $model backend\models\user\UpdateForm */

$this->title = Yii::t('app', 'Update music');

$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'Music Manager'),
    'url' => ['/music/index']
];
$this->params['breadcrumbs'][] = $this->title;

$js = "
$('form#{$model->formName()}').on('beforeSubmit', function(e, \$form) {
    var ajaxObj = Navar.ajaxLoadHtml('{$model->formName()}', 'update-container-add');
    ajaxObj.success(function(){
        Navar.reloadPjax('musicList-pjax');
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
        <?= common\widgets\Alert::widget(); ?>
        <?php
        $form = ActiveForm::begin([
//                        'id' => $model->formName(),
//                        'enableClientValidation' => true,
            'options' => ['enctype' => 'multipart/form-data']
        ]); ?>

        <div class="col-md-6">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title"><?= $this->title; ?></h3>
                </div>
                <div class="panel-body">

                    <?= $form->field($model, 'name')->textInput(['dir' => 'ltr']) ?>
                    <?= $form->field($model, 'key')->textInput(['dir' => 'ltr']) ?>
                    <?= $form->field($model, 'name_fa')->textInput() ?>
                    <?= $form->field($model, 'key_fa')->textInput() ?>
                    <?= $form->field($model, 'type')->dropDownList(Music::getTypeList()) ?>
                    <?= $form->field($model, 'genre_id')->dropDownList(Music::getGenreList()) ?>
                    <?= $form->field($model, 'special')->dropDownList(Music::getStatusList()) ?>
                    <?= $form->field($model, 'status')->dropDownList(Music::getStatusList()) ?>
                    <?= $form->field($model, 'status_fa')->dropDownList(Music::getStatusList()) ?>
                    <?= $form->field($model, 'status_app')->dropDownList(Music::getStatusList()) ?>
                    <?= $form->field($model, 'status_site')->dropDownList(Music::getStatusList()) ?>
                    <?= $form->field($model, 'image')->fileInput(['dir' => 'ltr']) ?>
                    <?= $form->field($model, 'lyric')->textarea(['dir' => 'ltr']) ?>
                    <?= $form->field($model, 'note')->textarea(['dir' => 'ltr']) ?>
                    <?= $form->field($model, 'note_fa')->textInput() ?>
                    <?= $form->field($model, 'note_app')->textInput() ?>
                    <?= $form->field($model, 'title_en')->textInput(['dir' => 'ltr']) ?>
                    <?= $form->field($model, 'title_fa')->textInput() ?>

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
                    <?= $form->field($model, 'music_no')->dropDownList([Music::getNumberList()]) ?>

                    <?= $form->field($model, 'created_at') ?>

                    <?= $form->field($model, 'title_en')->textInput(['dir' => 'ltr']) ?>
                    <?= $form->field($model, 'title_fa')->textInput() ?>
                    <?= $form->field($model, 'artist_name')->textInput(['dir' => 'ltr']) ?>
                    <?= $form->field($model, 'artist_name_fa')->textInput() ?>

                    <?= $form->field($model, 'key_pure')->textInput(['dir' => 'ltr']) ?>
                    <?= $form->field($model, 'dl_link')->textInput(['dir' => 'ltr']) ?>

                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title"><?= $this->title; ?></h3>
                </div>
                <div class="panel-body">

                    <?= $form->field($model, 'artist_id')->widget(\yii\jui\AutoComplete::classname(),[

                        'clientOptions' => [
                            'source' => $artist,
                            'autoFill'=>true,
                            'minLength'=>'2',
                            'select' => new JsExpression("function( event, ui ) {
                                $('#artist').val(ui.item.id);
                            }")
                        ],

                    ]) ?>

                    <div id="artists">
                        SINGER ------------------------------------------------------------------
                        <?= $form->field($model, 'singer_id[0]')->label(false)->widget(\yii\jui\AutoComplete::classname(),[

                            'clientOptions' => [
                                'source' => $artist,
                                'autoFill'=>true,
                                'minLength'=>'2',
                                'select' => new JsExpression("function( event, ui ) {
                                $('#artist').val(ui.item.id);
                            }")
                            ],

                        ]) ?>
                        <?= $form->field($model, 'singer_id[1]')->label(false)->widget(\yii\jui\AutoComplete::classname(),[

                            'clientOptions' => [
                                'source' => $artist,
                                'autoFill'=>true,
                                'minLength'=>'2',
                                'select' => new JsExpression("function( event, ui ) {
                                $('#artist').val(ui.item.id);
                            }")
                            ],

                        ]) ?>
                        <?= $form->field($model, 'singer_id[2]')->label(false)->widget(\yii\jui\AutoComplete::classname(),[

                            'clientOptions' => [
                                'source' => $artist,
                                'autoFill'=>true,
                                'minLength'=>'2',
                                'select' => new JsExpression("function( event, ui ) {
                                $('#artist').val(ui.item.id);
                            }")
                            ],

                        ]) ?>
                        <?= $form->field($model, 'singer_id[3]')->label(false)->widget(\yii\jui\AutoComplete::classname(),[

                            'clientOptions' => [
                                'source' => $artist,
                                'autoFill'=>true,
                                'minLength'=>'2',
                                'select' => new JsExpression("function( event, ui ) {
                                $('#artist').val(ui.item.id);
                            }")
                            ],

                        ]) ?>
                        <?= $form->field($model, 'singer_id[4]')->label(false)->widget(\yii\jui\AutoComplete::classname(),[

                            'clientOptions' => [
                                'source' => $artist,
                                'autoFill'=>true,
                                'minLength'=>'2',
                                'select' => new JsExpression("function( event, ui ) {
                                $('#artist').val(ui.item.id);
                            }")
                            ],

                        ]) ?>
                        LYRIC --------------------------------------------------------------------
                        <?= $form->field($model, 'lyric_id[0]')->label(false)->widget(\yii\jui\AutoComplete::classname(),[

                            'clientOptions' => [
                                'source' => $artist,
                                'autoFill'=>true,
                                'minLength'=>'2',
                                'select' => new JsExpression("function( event, ui ) {
                                $('#artist').val(ui.item.id);
                            }")
                            ],

                        ]) ?>
                        <?= $form->field($model, 'lyric_id[1]')->label(false)->widget(\yii\jui\AutoComplete::classname(),[

                            'clientOptions' => [
                                'source' => $artist,
                                'autoFill'=>true,
                                'minLength'=>'2',
                                'select' => new JsExpression("function( event, ui ) {
                                $('#artist').val(ui.item.id);
                            }")
                            ],

                        ]) ?>
                        <?= $form->field($model, 'lyric_id[2]')->label(false)->widget(\yii\jui\AutoComplete::classname(),[

                            'clientOptions' => [
                                'source' => $artist,
                                'autoFill'=>true,
                                'minLength'=>'2',
                                'select' => new JsExpression("function( event, ui ) {
                                $('#artist').val(ui.item.id);
                            }")
                            ],

                        ]) ?>
                        COMPOSER ------------------------------------------------------------
                        <?= $form->field($model, 'composer_id[0]')->label(false)->widget(\yii\jui\AutoComplete::classname(),[

                            'clientOptions' => [
                                'source' => $artist,
                                'autoFill'=>true,
                                'minLength'=>'2',
                                'select' => new JsExpression("function( event, ui ) {
                                $('#artist').val(ui.item.id);
                            }")
                            ],

                        ]) ?>
                        <?= $form->field($model, 'composer_id[1]')->label(false)->widget(\yii\jui\AutoComplete::classname(),[

                            'clientOptions' => [
                                'source' => $artist,
                                'autoFill'=>true,
                                'minLength'=>'2',
                                'select' => new JsExpression("function( event, ui ) {
                                $('#artist').val(ui.item.id);
                            }")
                            ],

                        ]) ?>
                        <?= $form->field($model, 'composer_id[2]')->label(false)->widget(\yii\jui\AutoComplete::classname(),[

                            'clientOptions' => [
                                'source' => $artist,
                                'autoFill'=>true,
                                'minLength'=>'2',
                                'select' => new JsExpression("function( event, ui ) {
                                $('#artist').val(ui.item.id);
                            }")
                            ],

                        ]) ?>
                        REGULATOR -----------------------------------------------------------
                        <?= $form->field($model, 'regulator_id[0]')->label(false)->widget(\yii\jui\AutoComplete::classname(),[

                            'clientOptions' => [
                                'source' => $artist,
                                'autoFill'=>true,
                                'minLength'=>'2',
                                'select' => new JsExpression("function( event, ui ) {
                                $('#artist').val(ui.item.id);
                            }")
                            ],

                        ]) ?>
                        <?= $form->field($model, 'regulator_id[1]')->label(false)->widget(\yii\jui\AutoComplete::classname(),[

                            'clientOptions' => [
                                'source' => $artist,
                                'autoFill'=>true,
                                'minLength'=>'2',
                                'select' => new JsExpression("function( event, ui ) {
                                $('#artist').val(ui.item.id);
                            }")
                            ],

                        ]) ?>
                        <?= $form->field($model, 'regulator_id[2]')->label(false)->widget(\yii\jui\AutoComplete::classname(),[

                            'clientOptions' => [
                                'source' => $artist,
                                'autoFill'=>true,
                                'minLength'=>'2',
                                'select' => new JsExpression("function( event, ui ) {
                                $('#artist').val(ui.item.id);
                            }")
                            ],

                        ]) ?>
                        MONTAGE --------------------------------------------------------------
                        <?= $form->field($model, 'montage_id[0]')->label(false)->widget(\yii\jui\AutoComplete::classname(),[

                            'clientOptions' => [
                                'source' => $artist,
                                'autoFill'=>true,
                                'minLength'=>'2',
                                'select' => new JsExpression("function( event, ui ) {
                                $('#artist').val(ui.item.id);
                            }")
                            ],

                        ]) ?>
                        <?= $form->field($model, 'montage_id[1]')->label(false)->widget(\yii\jui\AutoComplete::classname(),[

                            'clientOptions' => [
                                'source' => $artist,
                                'autoFill'=>true,
                                'minLength'=>'2',
                                'select' => new JsExpression("function( event, ui ) {
                                $('#artist').val(ui.item.id);
                            }")
                            ],

                        ]) ?>
                        <?= $form->field($model, 'montage_id[2]')->label(false)->widget(\yii\jui\AutoComplete::classname(),[

                            'clientOptions' => [
                                'source' => $artist,
                                'autoFill'=>true,
                                'minLength'=>'2',
                                'select' => new JsExpression("function( event, ui ) {
                                $('#artist').val(ui.item.id);
                            }")
                            ],

                        ]) ?>
                        MUSICIAN --------------------------------------------------------------
                        <?= $form->field($model, 'musician_id[0]')->label(false)->widget(\yii\jui\AutoComplete::classname(),[

                            'clientOptions' => [
                                'source' => $artist,
                                'autoFill'=>true,
                                'minLength'=>'2',
                                'select' => new JsExpression("function( event, ui ) {
                                $('#artist').val(ui.item.id);
                            }")
                            ],

                        ]) ?>
                        <?= $form->field($model, 'musician_id[1]')->label(false)->widget(\yii\jui\AutoComplete::classname(),[

                            'clientOptions' => [
                                'source' => $artist,
                                'autoFill'=>true,
                                'minLength'=>'2',
                                'select' => new JsExpression("function( event, ui ) {
                                $('#artist').val(ui.item.id);
                            }")
                            ],

                        ]) ?>
                        <?= $form->field($model, 'musician_id[2]')->label(false)->widget(\yii\jui\AutoComplete::classname(),[

                            'clientOptions' => [
                                'source' => $artist,
                                'autoFill'=>true,
                                'minLength'=>'2',
                                'select' => new JsExpression("function( event, ui ) {
                                $('#artist').val(ui.item.id);
                            }")
                            ],

                        ]) ?>
                    </div>
                    <div id="artistDirector">
                        DIRECTOR --------------------------------------------------------------
                        <?= $form->field($model, 'director_id[0]')->label(false)->widget(\yii\jui\AutoComplete::classname(),[

                            'clientOptions' => [
                                'source' => $artist,
                                'autoFill'=>true,
                                'minLength'=>'2',
                                'select' => new JsExpression("function( event, ui ) {
                                $('#artist').val(ui.item.id);
                            }")
                            ],

                        ]) ?>
                        <?= $form->field($model, 'director_id[1]')->label(false)->widget(\yii\jui\AutoComplete::classname(),[

                            'clientOptions' => [
                                'source' => $artist,
                                'autoFill'=>true,
                                'minLength'=>'2',
                                'select' => new JsExpression("function( event, ui ) {
                                $('#artist').val(ui.item.id);
                            }")
                            ],

                        ]) ?>
                        <?= $form->field($model, 'director_id[2]')->label(false)->widget(\yii\jui\AutoComplete::classname(),[

                            'clientOptions' => [
                                'source' => $artist,
                                'autoFill'=>true,
                                'minLength'=>'2',
                                'select' => new JsExpression("function( event, ui ) {
                                $('#artist').val(ui.item.id);
                            }")
                            ],

                        ]) ?>
                    </div>

                    <?= $form->field($model, 'tag')->textarea(['dir' => 'ltr']) ?>

                    <?= $form->field($model, 'music1080Upload')->fileInput() ?>
                    <?= $form->field($model, 'music1080Address')->textInput(['dir' => 'ltr']) ?>

                    <?= $form->field($model, 'music720Upload')->fileInput() ?>
                    <?= $form->field($model, 'music720Address')->textInput(['dir' => 'ltr']) ?>

                    <?= $form->field($model, 'music480Upload')->fileInput() ?>
                    <?= $form->field($model, 'music480Address')->textInput(['dir' => 'ltr']) ?>

                    <?= $form->field($model, 'music320Upload')->fileInput() ?>
                    <?= $form->field($model, 'music320Address')->textInput(['dir' => 'ltr']) ?>

                    <?= $form->field($model, 'music128Upload')->fileInput() ?>
                    <?= $form->field($model, 'music128Address')->textInput(['dir' => 'ltr']) ?>

                    <?= $form->field($model, 'hd')->dropDownList(Music::getStatusList()) ?>
                    <?= $form->field($model, 'hq')->dropDownList(Music::getStatusList()) ?>
                    <?= $form->field($model, 'lq')->dropDownList(Music::getStatusList()) ?>

                </div>
            </div>
        </div>

        <div class="form-group">
            <?= Html::submitButton(Yii::t('app', 'Update'), ['class' => 'btn btn-primary btn-block']) ?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>