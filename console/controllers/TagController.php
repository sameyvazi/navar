<?php

namespace console\controllers;

use yii\console\Controller;

class TagController extends Controller {
    
    /**
     * @inheritdoc
     */
    public function actions() {
        return [
            'artist' => \console\controllers\tag\Artists::class,
            'music' => \console\controllers\tag\Musics::class,
            'full-album-artist' => \console\controllers\tag\FullAlbumArtist::class,
        ];
    }

}