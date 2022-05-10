<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\user\User */

$this->title = Yii::t('app', 'User'). " - {$model->username}";
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'Users'),
    'url' => ['/user/index']
];
$this->params['breadcrumbs'][] = Html::encode($model->username);

//$devices = '<div dir="ltr">';
//
//if (is_array($model->devices))
//{
//    foreach($model->devices as $device)
//    {
//        $devices .= "UUID: {$device['uuid']}<br>";
//        $devices .= "Model: {$device['model']}";
//        if (isset($device['fcm']))
//        {
//            $devices .= "<br>FCM: {$device['fcm']}";
//        }
//        if (isset($device['platform']))
//        {
//            $devices .= "<br>Platform: {$device['platform']}";
//        }
//        $devices .= '<hr>';
//    }
//}
//
//$devices .= '</div>';

?>
<?= DetailView::widget([
    'model' => $model,
    'attributes' => [
        'id',
        'mobile',
        'username',
        'email',
        'name',
        'lastname',
        [
            'label' => Yii::t('app', 'Status'),
            'value' => $model->getStatusText($model->status)
        ],
        [
            'label' => Yii::t('app', 'Created at'),
            'value' => Yii::$app->getFormatter()->asDatetime($model->created_at)
        ],
//        [
//            'label' => Yii::t('app', 'Devices'),
//            'format' => 'HTML',
//            'value' => $devices
//        ],
        'devices',
        [
            'label' => Yii::t('app', 'Avatar'),
            'format' => 'image',
            'value' => $model->getAvatarUrl($model->avatar, 0)
        ],
    ]
]); ?>