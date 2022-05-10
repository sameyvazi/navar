<?php

$config = [
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => '_P0gPtdrvWAmTafe9qfiPpDe2Q9p7BS9',
        ]
    ],
];

if (YII_DEBUG) {
//    $config['bootstrap'][] = 'debug';
//    $config['modules']['debug'] = [
//        'class' => yii\debug\Module::class,
//        'panels' => [
//            'mongodb' => [
//                'class' => yii\mongodb\debug\MongoDbPanel::class,
//                'db' => 'mongodb'
//            ],
//        ],
//    ];

//    $config['bootstrap'][] = 'gii';
//    $config['modules']['gii'] = [
//        'class' => yii\gii\Module::class,
//        'generators' => [
//            'mongoDbModel' => [
//                'class' => yii\mongodb\gii\model\Generator::class
//            ]
//        ],
//    ];


    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = 'yii\debug\Module';

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii']=[
        'class' =>  'yii\gii\Module',
        'allowedIPs' => ['*'],
    ];
}

return $config;
