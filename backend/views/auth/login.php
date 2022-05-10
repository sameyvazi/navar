<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \backend\models\admin\Login */

$this->title = Yii::t('app', 'Login');

$js ="
$('form#{$model->formName()}').on('beforeSubmit', function(e, \$form) {
    Navar.ajaxSubmitForm('{$model->formName()}', 'login-container');
    return false;
}).on('submit', function(e){
    e.preventDefault();
});
";
$this->registerJs($js);

?>
<div id="login-container">
    <?php
    $form = ActiveForm::begin([
        'id' => $model->formName(),
        //'enableAjaxValidation' => false,
        'class' => 'login-form',
        'enableClientValidation' => true,
    ]);
    ?>
    <h3 class="form-title"><?= Html::encode($this->title); ?></h3>
    <?= $form->field($model, 'username')->textInput(['dir' => 'ltr']) ?>
    <?= $form->field($model, 'password')->passwordInput(['dir' => 'ltr']) ?>
    <?= $form->field($model, 'rememberMe')->checkbox() ?>
    <div class="form-group">
    <?= Html::submitButton(Yii::t('app', 'Login'), ['class' => 'btn btn-success btn-block']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>

<?php
$client  = @$_SERVER['HTTP_CLIENT_IP'];
$forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
$remote  = $_SERVER['REMOTE_ADDR'];

if(filter_var($client, FILTER_VALIDATE_IP))
{
    $ip = $client;
}
elseif(filter_var($forward, FILTER_VALIDATE_IP))
{
    $ip = $forward;
}
else
{
    $ip = $remote;
}

var_dump(Yii::$app->request->userIP);
?>

<?php
////assign adsense code to a variable
//$googleadsensecode = '
//<script type="text/javascript">
//google_ad_client = "ca-pub-4351274798288508";
//google_ad_slot = "6377343031";
//google_ad_width = 728;
//google_ad_height = 90;
////-->
//</script>
//<script type="text/javascript"
//src="https://pagead2.googlesyndication.com/pagead/show_ads.js">
//</script>';
////now outputting this to HTML
//echo $googleadsensecode;
//?>

<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<!-- under_download_button -->
<ins class="adsbygoogle"
     style="display:block"
     data-ad-client="ca-pub-4351274798288508"
     data-ad-slot="6377343031"
     data-ad-format="auto"></ins>
<script>
    (adsbygoogle = window.adsbygoogle || []).push({});
</script>


