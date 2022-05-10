<?php

namespace console\controllers;

use yii\console\Controller;

class AdminController extends Controller {
    
    /**
     * @inheritdoc
     */
    public function actions() {
        return [
            'create' => \console\controllers\admin\Create::class,
        ];
    }

}