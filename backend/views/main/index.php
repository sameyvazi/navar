<?php

use yii\helpers\Url;

/* @var $this yii\web\View */

$this->title = Yii::t('app', "Administrator");

?>
<?php /* <a class="btn btn-app">
  <span class="badge bg-yellow">3</span>
  <i class="glyphicon glyphicon-cd"></i>
  test
  </a> */ ?>
<div class="container">
    <div class="row well well-sm">
        <div class="col-md-12" id="maintenance">
            <legend><?= Yii::t('app', 'Maintenance'); ?></legend>
            <div class="btn-group" role="group" aria-label="...">
                <?php if (Yii::$app->getUser()->can('ClearCache')): ?>
                <div class="btn-group" role="group">
                    <button onclick="Navar.clearCacheAndAssets('<?= Url::to(['/main/clear-cache']); ?>', 'maintenance');" type="button" class="btn btn-primary" role="button"><?= Yii::t('app', 'Clear system cache'); ?></button>
                </div>
                <?php endif; ?>
                <?php if (Yii::$app->getUser()->can('ClearWebAssets')): ?>
                <div class="btn-group" role="group">
                    <button onclick="Navar.clearCacheAndAssets('<?= Url::to(['/main/clear-assets']); ?>', 'maintenance');" type="button" class="btn btn-primary" role="button"><?= Yii::t('app', 'Clear website assets'); ?></button>
                </div>
                <?php endif; ?>
                <?php if (Yii::$app->getUser()->can('ClearCacheImage')): ?>
                <div class="btn-group" role="group">
                    <button onclick="Navar.clearCacheAndAssets('<?= Url::to(['/main/clear-thumbnails']); ?>', 'maintenance');" type="button" class="btn btn-primary" role="button"><?= Yii::t('app', 'Clear thumbnails images'); ?></button>
                </div>
                <?php endif; ?>
                <?php if (Yii::$app->getUser()->can('ClearLog')): ?>
                <div class="btn-group" role="group">
                    <button onclick="Navar.clearCacheAndAssets('<?= Url::to(['/main/clear-log']); ?>', 'maintenance');" type="button" class="btn btn-primary" role="button"><?= Yii::t('app', 'Clear Log'); ?></button>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!--<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>-->
<!--<ins class="adsbygoogle"-->
<!--     style="display:block"-->
<!--     data-ad-client="ca-pub-4351274798288508"-->
<!--     data-ad-slot="6377343031"-->
<!--     data-ad-format="auto"></ins>-->
<!--<script>-->
<!--    (adsbygoogle = window.adsbygoogle || []).push({});-->
<!--</script>-->