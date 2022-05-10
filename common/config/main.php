<?php
return [
    'version' => '1.1.9',
    'name' => 'Navar',
    'charset' => 'UTF-8',
    'timeZone' => 'Asia/Tehran',
    'language' => 'fa-IR',
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'bootstrap' => ['log'],
    'components' => [
        'user' => [
            'class' => yii\web\User::class,
        ],
        'authManager' => [
            'class' => yii\rbac\DbManager::class,
            'cache' => 'cache',
            'cacheKey' => 'rbac_backend',
        ],
        'i18n' => [
            'translations' => [
                'yii' => [
                    'class' => yii\i18n\PhpMessageSource::class,
                    'basePath' => '@vendor/yiisoft/yii2/messages',
                    'sourceLanguage' => 'en-US',
                ],
                'app' => [
                    'class' => yii\i18n\GettextMessageSource::class,
                    'basePath' => '@common/messages',
                ],
            ],
        ],
        'formatter' => [
            'locale' => 'fa_IR@calendar=persian',
            //'calendar' => \IntlDateFormatter::TRADITIONAL,
        ],
        'dateTimeAction' => \common\components\DateTimeAction::class,
        'email' => \common\components\Email::class,
        'imageHelper' => \common\components\ImageHelper::class,
        'sms' => \common\components\Sms::class,
        'ip' => \common\components\Ip::class,
        'tags' => \common\components\Tags::class,
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
        ],
    ],
];
