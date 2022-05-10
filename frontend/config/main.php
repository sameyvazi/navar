<?php

$params = array_merge(
        require(__DIR__ . '/../../common/config/params.php'),
        require(__DIR__ . '/../../common/config/params-local.php'),
        require(__DIR__ . '/params.php'),
        require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'deals-frontend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'frontend\controllers',
    'defaultRoute' => 'payment/index',
    'components' => [
        'user' => [
            'class' => \yii\web\User::class,
            'enableAutoLogin' => false,
            'enableSession' => false,
            'loginUrl' => null,
        ],
        'request' => [
            'csrfParam' => 'digifc_frontend_csrf',
            'enableCsrfValidation' => false,
        ],
        'urlManager' => [
            'class' => \yii\web\UrlManager::class,
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                '<id:\w+>' => 'payment/index',
                '<controller>/<action>/<id:\w+>' => '<controller>/<action>',
            ]
        ],
        'assetManager' => [
            'baseUrl' => $params['staticUrl'] . '/frontend/assets',
            'basePath' => '@static/frontend/assets',
            'linkAssets' => true,
            'appendTimestamp' => true,
            'bundles' => [
                "frontend\assets\FrontendAppAsset" => false,
                "frontend\assets\BackendAppAsset" => false,
                "yii\web\YiiAsset" => false,
                'yii\validators\ValidationAsset' => false,
                'yii\web\JqueryAsset' => false,
                'yii\bootstrap\BootstrapAsset' => false,
                "backend\assets\AppAsset" => false,
                "yii\bootstrap\BootstrapPluginAsset" => false,
                "backend\assets\MouseWheelAsset" => false,
                "backend\assets\FontAwesomeAsset" => false,
                "backend\assets\BootstrapHoverDropdownAsset" => false,
                "backend\assets\JqueryScrollToAsset" => false,
                "backend\assets\SlimScrollAsset" => false,
                "backend\assets\SmoothScrollAsset" => false,
                "backend\assets\BlockUi" => false,
                "yii\widgets\MaskedInputAsset" => false,
                "yii\widgets\ActiveFormAsset" => false,
                "yii\grid\GridViewAsset" => false,
                "yii\widgets\PjaxAsset" => false,
                "mdm\admin\AnimateAsset" => false,
                'backend\assets\HighStockAsset' => false,
                'kartik\time\TimePickerAsset' => false,
                "kartik\base\WidgetAsset" => false,
            ]
        ],
        'errorHandler' => [
            'errorAction' => 'payment/error',
        ],
        'helper' => [
            'class' => \frontend\components\Helper::class,
        ],
    ],
    'params' => $params,
];
