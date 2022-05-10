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
    'bootstrap' => [],
    'modules' => [
        'f1' => [
            'basePath' => '@app/modules/api/frontend/v1',
            'class' => api\modules\api\frontend\v1\Module::class
        ]
    ],
    'components' => [
        'request' => [
            'enableCsrfValidation' => false,
            'enableCookieValidation' => false,
            'parsers' => [
                'application/json' => yii\web\JsonParser::class,
                'multipart/form-data' => yii\web\MultipartFormDataParser::class,
            ]
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'enableStrictParsing' => true,
            'showScriptName' => false,
            'rules' => []// require __DIR__  . '/routing.php',
        ],
        'response' => [
            'format' => yii\web\Response::FORMAT_JSON,
        ],
        'moduleUrlRules' => [
            'class' => sheershoff\ModuleUrlRules\ModuleUrlRules::class,
            'allowedModules' => ['f1'],
        ],
        'helper' => api\components\Helper::class,
    ],
    'params' => $params,
];