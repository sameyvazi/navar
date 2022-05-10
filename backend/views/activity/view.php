<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Json;

/* @var $this yii\web\View */
/* @var $model common\models\activity\Activity */


$this->title = Yii::t('app', 'Activity'). " - {$model->user_id}";
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'Activity'),
    'url' => ['/activity/index']
];
$this->params['breadcrumbs'][] = Html::encode($model->user_id);
?>
<?= DetailView::widget([
    'model' => $model,
    'attributes' => [
        'user_id',
        [
            'label'=> 'data',
            'format' => 'html',
            'value' => '<pre dir="ltr">' . print_r(Json::decode($model->data, true), true) . '</pre>'
        ],
        'ip',
        'target_id',
        'entity',
        [
            'attribute' => 'created_at',
            'filterInputOptions' => [
                'class' => 'form-control',
                'dir' => 'ltr',
                'placeholder' => Yii::$app->formatter->asDatetime(time(), 'php:Y-m-d_Y-m-d')
            ],
            'value' => function ($data) {
                return Yii::$app->formatter->asDatetime($data->created_at, 'php:l j F Y H:i (Y/m/d)');
            }
        ],

    ]
]); ?>