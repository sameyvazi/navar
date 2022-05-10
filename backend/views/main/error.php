<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

$this->title = $name;
?>
<div class="container">
    <div class="error-page-wrapper">
        <h1 style="text-align: center; direction: ltr"><?= Html::encode($this->title) ?></h1>

        <div class="alert alert-danger">
            <?= nl2br(Html::encode($message)) ?>
        </div>

        <p>
            <?= Yii::t('app', 'The above error occurred while the Web server was processing your request.'); ?>
        </p>
        <p>
            <?= Yii::t('app', 'Error sent to website administrator for check the detail.'); ?>
        </p>
    </div>
</div>