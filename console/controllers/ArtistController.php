<?php

namespace console\controllers;

use yii\console\Controller;

class ArtistController extends Controller {
    
    /**
     * @inheritdoc
     */
    public function actions() {
        return [
            'index' => \console\controllers\artist\Index::class,
        ];
    }

}