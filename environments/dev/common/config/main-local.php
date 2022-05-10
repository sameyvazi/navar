<?php
return [
    'components' => [
        'db' => [
            'class' => yii\db\Connection::class,
            'dsn' => 'mysql:host=127.0.0.1;dbname=navar',
            'username' => 'root',
            'password' => 'root',
            'charset' => 'utf8',
            'enableSchemaCache' => !YII_DEBUG,
            'schemaCacheDuration' => 1209600, //2 week
        ],
//        'mongodb' => [
//            'class' => yii\mongodb\Connection::class,
//            'dsn' => 'mongodb://localhost:27017/digifc_db',
//            'enableLogging' => YII_DEBUG,
//            'enableProfiling' => YII_DEBUG,
//            //'dsn' => 'mongodb://username:password@localhost:27017/blue',
//        ],
        'redis' => [
            'class' => yii\redis\Connection::class,
            'hostname' => 'localhost',
            'port' => 6379,
            'database' => null,
        ],
        'cache' => [
            'class' => \yii\redis\Cache::class,
            'keyPrefix' => 'navar-m',
            //'serializer' => ['igbinary_serialize', 'igbinary_unserialize'],
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                /*                [
                                    'class' => yii\mongodb\log\MongoDbTarget::class,
                                    'levels' => ['error', 'warning'],
                                    'exportInterval' => 1,
                                    'except' => [
                                        'yii\web\HttpException:404',
                                        'yii\web\HttpException:422',
                                        'yii\web\HttpException:401',
                                        'yii\web\HttpException:403',
                                    ],
                                ],*/
                [
                    'class' => yii\log\FileTarget::class,
                    'levels' => ['error', 'warning'],
                    'exportInterval' => 1,
                    'categories' => [
                        'yii\db\*',
                        //'yii\mongodb\*',
                        'yii\web\HttpException:*',
                    ],
                    'except' => [
                        'yii\web\HttpException:404',
                        'yii\web\HttpException:422',
                        'yii\web\HttpException:401',
                        'yii\web\HttpException:403',
                    ],
                ],
                [
                    'class' => yii\log\EmailTarget::class,
                    'levels' => ['error'],
                    'exportInterval' => 1,
                    'categories' => ['yii\db\*', 'yii\mongodb\*', 'yii\redis\*', 'yii\caching\*'],
                    'message' => [
                        'from' => 'log@navar.ir',
                        'to' => ['info@navar.ir'],
                        'subject' => 'Error on navar.ir',
                    ],
                ],
            ],
        ],
        'mailer' => [
            'class' => yii\swiftmailer\Mailer::class,
            'viewPath' => '@common/mail',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => YII_DEBUG,
            //fileTransportPath
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                //'class' => 'Swift_FailoverTransport',
                'host' => 'smtp.mailgun.org',
                'username' => 'postmaster@sandboxbab19eabb2394972b51c57eaa5c5d211.mailgun.org',
                'password' => '123456789715867',
                //'port' => '465',
                //'encryption' => 'ssl',
            ],
        ],
    ],
];
