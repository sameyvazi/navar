<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'navar',
    'charset' => 'UTF-8',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'defaultRoute' => '/main',
    'modules' => require __DIR__  . '/modules.php',
    'bootstrap' => [],
    'components' => [
        'request' => [
            'csrfParam' => 'navar_backend_csrf',
            'enableCsrfValidation' => true,
        ],
        'urlManager' => [
            'class' => yii\web\UrlManager::class,
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'suffix' => '.html',
            'rules' => [
                '' => 'main/index',
                
                '<controller:application>/<action:add>/<type:\w+>' => '<controller>/<action>',
                '<controller:application>/<action:update>/<type:\w+>/<id:\w+>' => '<controller>/<action>',
                
                '<module:roles>/<controller>/<action>/<id:\w+>' => '<module>/<controller>/<action>',
                '<module:roles>/<controller>/<action>' => '<module>/<controller>/<action>',
                
                '<controller>/<action>/<id:\w+>' => '<controller>/<action>',

            ]
        ],
        'user' => [
            'identityClass' => \common\models\admin\Admin::class,
            'loginUrl' => ['/auth/login'],
            'enableAutoLogin' => true,
            'identityCookie' => [
                'name' => 'navar_backendUser',
                'httpOnly' => true,
            ]
        ],
        'session' => [
            'class' => yii\redis\Session::class,
            'redis' => 'redis',
            'name' => 'navar_backend_session',
        ],
        'assetManager' => [
            'baseUrl' => $params['staticUrl'] . '/backend/assets',
            'basePath' => '@static/backend/assets',
            'linkAssets' => true,
            'appendTimestamp' => true,
            'bundles' => [
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
                'backend\assets\BootstrapRtlAsset' => false,
                'backend\assets\DropZoneAsset' => false,
            ]
        ],
        'errorHandler' => [
            'errorAction' => 'main/error',
        ],
        'helper' => backend\components\Helper::class
    ],
    'params' => $params,
];
