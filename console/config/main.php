<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'navar',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'console\controllers',
    'controllerMap' => [
        'migrate' => [
            'class' => \yii\console\controllers\MigrateController::class,
            'migrationPath' => '@app/migrations/mysql',
            'migrationNamespaces' => [
                //'yii\rbac\migrations',
            ],
        ],
//        'mongodb-migrate' => [
//            'class' => \yii\mongodb\console\controllers\MigrateController::class,
//            'migrationPath' => '@app/migrations/mongodb'
//        ],
    ],
    'components' => [
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
        'helper' => [
            'class' => common\components\Helper::class,
        ],
    ],
    'params' => $params,
];
