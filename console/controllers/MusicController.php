<?php

namespace console\controllers;

use yii\console\Controller;

class MusicController extends Controller {
    
    /**
     * @inheritdoc
     */
    public function actions() {
        return [
            'album' => \console\controllers\music\Album::class,
            'mp3' => \console\controllers\music\Mp3::class,
            'video' => \console\controllers\music\Video::class,
            'like' => \console\controllers\music\Like::class,
            'likeFa' => \console\controllers\music\LikeFa::class,
            'comment' => \console\controllers\music\Comment::class,
            'artist' => \console\controllers\music\Artist::class,
            'tags' => \console\controllers\music\Tags::class,
            'quality' => \console\controllers\music\Quality::class,
            'alert' => \console\controllers\music\Alert::class,
        ];
    }

}