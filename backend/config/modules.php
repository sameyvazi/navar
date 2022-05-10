<?php

return [
    'roles' => [
        'class' => \mdm\admin\Module::class,
        'layout' => 'left-menu',
        'mainLayout' => '@app/views/layouts/main.php',
        'defaultRoute' => 'assignment',
        'controllerMap' => [
            'assignment' => [
                'class' => \mdm\admin\controllers\AssignmentController::class,
                'userClassName' => \common\models\admin\Admin::class,
                'usernameField' => 'username',
                'idField' => 'id',
                //'searchClass' => \backend\models\roles\AssignmentSearch::class
            ],
        ],
        'menus' => [
            'route' => null,
            'rule' => null,
            'menu' => null,
            'default' => null
        ],
        'as access' => [
            'class' => \yii\filters\AccessControl::class,
            'rules' => [
                [
                    'allow' => true,
                    'roles' => ['Roles'],
                ],
            ]
        ],
    ],
];
