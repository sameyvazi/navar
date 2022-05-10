<?php

namespace api\modules\api\frontend\v1\controllers\artist;

use api\modules\api\frontend\v1\models\artist\ArtistMusicSearch;
use common\models\music\MusicArtist;
use yii\base\Action;
use yii\data\Pagination;

class Music extends Action {

    /**
     * @api {get} /artists/:id/music/:activity Artist Musics
     * @apiVersion 1.0.0
     * @apiName ArtistMusic
     * @apiGroup Artist
     *
     * @apiParam (Params) {Integer} id Artist identity code
     *
     * @apiParam (Params) {Integer} activity Artist activity code
     *
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
    [
        {
            "id": 42,
            "activity": {
                "key": 8,
                "name": "Singer"
            },
            "music": {
                "id": 12,
                "key": "download-music-z-a",
                "key_fa": "دانلود-آهنگ-z-a",
                "type": {
                    "key": 1,
                    "value": "Mp3"
                },
                "name": "a",
                "name_fa": "a",
                "artist_id": 26,
                "special": 2,
                "music_id": null,
                "music_no": 0,
                "lyric": "",
                "note": "",
                "note_fa": "",
                "note_app": "",
                "like": 0,
                "like_fa": 0,
                "like_app": 0,
                "view": 5,
                "view_fa": 0,
                "view_app": 37,
                "play": 0,
                "play_fa": 0,
                "play_app": 0,
                "time": "",
                "directory": "z",
                "dl_link": "z - a",
                "image": "z - a.jpg",
                "key_pure": "z-a",
                "artist": {
                    "id": 26,
                    "name": "z",
                    "name_fa": "z",
                    "activity": "Singer",
                    "image": "z.jpg",
                    "like": 0,
                    "like_fa": 0,
                    "like_app": 0,
                    "status": 1,
                    "status_fa": 1,
                    "status_app": 1,
                    "key": "z",
                    "key_fa": "z",
                    "link": "/f1/artists/z"
                },
                "link": {
                    "128": "http://localhost/navar/backend/web/upload/z/mp3/z - a[128].mp3",
                    "320": "http://localhost/navar/backend/web/upload/z/mp3/z - a[320].mp3"
                }
            }
        }
    ]
     *
     *
     * @apiError (Error 401)Unauthorized Login required
     *
     * @apiError (Error 404)NotFound Type required
     *
     *
     */
    public function run() {

        $duration = 604000; //1 week

        return ArtistMusicSearch::getDb()->cache(function ($db) {
            return (new ArtistMusicSearch)->search(\Yii::$app->getRequest()->get());
        }, $duration);


    }

}
