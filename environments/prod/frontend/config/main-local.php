<?php
return [
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => '',
        ],
        'session' => [
            'class' => \yii\redis\Session::class,
            'redis' => 'redis',
            'name' => 'digifc_frontend_session',
        ],
    ],
];
