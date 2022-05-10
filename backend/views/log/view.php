<?php

use yii\helpers\Html;
use yii\helpers\HtmlPurifier;
/* @var $this yii\web\View */
/* @var $model backend\models\log\Log */

$this->title = Yii::t('app', 'Log Manager'). " - {$model->getPrimaryKey()}: {$model->category}";
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'Log Manager'),
    'url' => ['/log/index']
];
$this->params['breadcrumbs'][] = Html::encode("{$model->getPrimaryKey()}: {$model->category}");

?>
<pre style="direction: ltr">
    <?= HtmlPurifier::process($model->message); ?>
</pre>