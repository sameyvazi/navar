<?php

use backend\assets\AppAsset;
use yii\helpers\Html;

/* @var $this \yii\web\View */
/* @var $content string */

$bundle = AppAsset::register($this);

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<!--[if IE 8]> <html lang="<?= Yii::$app->language ?>" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="<?= Yii::$app->language ?>" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="<?= Yii::$app->language ?>" dir="rtl">
<!--<![endif]-->
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta content="width=device-width, initial-scale=1" name="viewport" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="robots" content="noindex,nofollow"/>
        <link rel="icon" href="<?= Yii::$app->params['staticUrl'] . '/static-images/logo2.png' ?>" type="image/x-icon"/>
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?= Html::cssFile(Yii::$app->params['staticUrl'].'/backend/styles/all.css?v=' . filemtime(Yii::getAlias('@static/backend/styles/all.css'))) ?>
        <?php $this->head() ?>
    </head>
    <body class="page-md login">
        <?php $this->beginBody() ?>
        <div class="menu-toggler sidebar-toggler">
        </div>
        <div class="logo">
            <a href="">
                <img src="<?= Yii::$app->params['staticUrl']; ?>/static-images/logo.png" alt="Navar" width="250"/>
            </a>
        </div>
        <div class="content">
            <?= $content ?>
        </div>
        <div class="copyright">
            <?= Yii::$app->getFormatter()->asDate(time(), 'php:Y'); ?> &copy; <?= Yii::t('app', 'Navar Version {version}', ['version' => Yii::$app->version]); ?>
        </div>
        <?= Html::jsFile(Yii::$app->params['staticUrl'] . '/backend/scripts/all.js?v=' . filemtime(Yii::getAlias('@static/backend/scripts/all.js'))) ?>
        <?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage() ?>