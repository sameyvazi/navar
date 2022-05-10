<?php

namespace api\modules\api\frontend\v1\controllers\artist;

use api\modules\api\frontend\v1\models\artist\ArtistPage;
use yii\base\Action;
use Yii;

class Page extends Action {

    /**
     * @api {get} /artists/:id/page Artist Page
     * @apiVersion 1.0.0
     * @apiName ArtistPage
     * @apiGroup Artist
     *
     * @apiParam (Params) {Integer} id Artist identity code
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *
    {
        "latest_release": {
            "id": 27,
            "key": "download-album-z-f3",
            "key_fa": "دانلود آلبوم z-f3",
            "type": {
            "key": 3,
            "value": "Album"
            },
            "name": "f3",
            "name_fa": "f3",
            "artist_id": 26,
            "special": null,
            "music_id": null,
            "music_no": 0,
            "lyric": "",
            "note": "",
            "note_fa": "",
            "note_app": "",
            "like": null,
            "like_fa": null,
            "like_app": null,
            "view": 14,
            "view_fa": 0,
            "view_app": 133,
            "play": 0,
            "play_fa": 0,
            "play_app": 0,
            "time": "",
            "directory": "z",
            "dl_link": "z - f3",
            "image": "z - f3.jpg",
            "key_pure": "z-f3",
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
            "link": null
        },
        "popular": [
            {
                "id": 44,
                "key": "download-music-z-aaa-bbb",
                "key_fa": "دانلود-آهنگ-z-aaa-bbb",
                "type": {
                "key": 1,
                "value": "Mp3"
                },
                "name": "aaa bbb",
                "name_fa": "aaa bbb",
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
                "view": 4,
                "view_fa": 0,
                "view_app": 3,
                "play": 0,
                "play_fa": 0,
                "play_app": 0,
                "time": "",
                "directory": "z",
                "dl_link": "z - aaa bbb",
                "image": "z - aaa bbb.jpg",
                "key_pure": "z-aaa-bbb",
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
                "320": "http://localhost/navar/backend/web/upload/z/mp3/z - aaa bbb[320].mp3",
                "128": "http://localhost/navar/backend/web/upload/z/mp3/z - aaa bbb[128].mp3"
                }
            },
            {
                "id": 43,
                "key": "download-music-z-26",
                "key_fa": "دانلود-آهنگ-z-26",
                "type": {
                "key": 1,
                "value": "Mp3"
                },
                "name": "26",
                "name_fa": "26",
                "artist_id": 26,
                "special": 2,
                "music_id": 27,
                "music_no": 4,
                "lyric": "",
                "note": "",
                "note_fa": "",
                "note_app": "",
                "like": 0,
                "like_fa": 0,
                "like_app": 0,
                "view": 4,
                "view_fa": 0,
                "view_app": 94,
                "play": 0,
                "play_fa": 0,
                "play_app": 0,
                "time": "",
                "directory": "z",
                "dl_link": "04 z - 26",
                "image": "z - 26.jpg",
                "key_pure": "z-26",
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
                "320": "http://localhost/navar/backend/web/upload/z/mp3/04 z - 26[320].mp3",
                "128": "http://localhost/navar/backend/web/upload/z/mp3/04 z - 26[128].mp3"
                }
            }
        ],
        "albums": [
            {
                "album": {
                    "id": 26,
                    "key": "download-album-z-f2",
                    "key_fa": "دانلود آلبوم z-f2",
                    "type": {
                    "key": 3,
                    "value": "Album"
                    },
                    "name": "f2",
                    "name_fa": "f2",
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
                    "view_app": 50,
                    "play": 0,
                    "play_fa": 0,
                    "play_app": 0,
                    "time": "",
                    "directory": "z",
                    "dl_link": "z - f2",
                    "image": "z - f2.jpg",
                    "key_pure": "z-f2",
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
                    "link": null
                },
                "music_count": "3",
                "music": [
                    {
                        "id": 28,
                        "key": "download-music-z-f3",
                        "key_fa": "دانلود-آهنگ-z-f3",
                        "type": {
                        "key": 1,
                        "value": "Mp3"
                        },
                        "name": "f3",
                        "name_fa": "f3",
                        "artist_id": 26,
                        "special": 2,
                        "music_id": 26,
                        "music_no": 1,
                        "lyric": "",
                        "note": "",
                        "note_fa": "",
                        "note_app": "",
                        "like": 0,
                        "like_fa": 0,
                        "like_app": 0,
                        "view": 5,
                        "view_fa": 0,
                        "view_app": 41,
                        "play": 0,
                        "play_fa": 0,
                        "play_app": 0,
                        "time": "",
                        "directory": "z",
                        "dl_link": "z - f3",
                        "image": "z - f3.jpg",
                        "key_pure": "z-f3",
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
                        "320": "http://localhost/navar/backend/web/upload/z/mp3/z - f3[320].mp3",
                        "128": "http://localhost/navar/backend/web/upload/z/mp3/z - f3[128].mp3"
                        }
                    },
                    {
                        "id": 29,
                        "key": "download-music-z-f4",
                        "key_fa": "دانلود-آهنگ-z-f4",
                        "type": {
                        "key": 1,
                        "value": "Mp3"
                        },
                        "name": "f4",
                        "name_fa": "f4",
                        "artist_id": 26,
                        "special": 2,
                        "music_id": 26,
                        "music_no": 2,
                        "lyric": "",
                        "note": "",
                        "note_fa": "",
                        "note_app": "",
                        "like": 0,
                        "like_fa": 0,
                        "like_app": 0,
                        "view": 5,
                        "view_fa": 0,
                        "view_app": 41,
                        "play": 0,
                        "play_fa": 0,
                        "play_app": 0,
                        "time": "",
                        "directory": "z",
                        "dl_link": "z - f4",
                        "image": "z - f4.jpg",
                        "key_pure": "z-f4",
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
                        "320": "http://localhost/navar/backend/web/upload/z/mp3/z - f4[320].mp3",
                        "128": "http://localhost/navar/backend/web/upload/z/mp3/z - f4[128].mp3"
                        }
                    },
                    {
                        "id": 30,
                        "key": "download-music-z-gg",
                        "key_fa": "دانلود-آهنگ-z-gg",
                        "type": {
                        "key": 1,
                        "value": "Mp3"
                        },
                        "name": "gg",
                        "name_fa": "gg",
                        "artist_id": 26,
                        "special": 2,
                        "music_id": 26,
                        "music_no": 3,
                        "lyric": "",
                        "note": "",
                        "note_fa": "",
                        "note_app": "",
                        "like": 0,
                        "like_fa": 0,
                        "like_app": 0,
                        "view": 5,
                        "view_fa": 0,
                        "view_app": 41,
                        "play": 0,
                        "play_fa": 0,
                        "play_app": 0,
                        "time": "",
                        "directory": "z",
                        "dl_link": "z - gg",
                        "image": "z - gg.jpg",
                        "key_pure": "z-gg",
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
                        "320": "http://localhost/navar/backend/web/upload/z/mp3/z - gg[320].mp3",
                        "128": "http://localhost/navar/backend/web/upload/z/mp3/z - gg[128].mp3"
                        }
                    }
                ]
            }
        ],
        "single_track": [
            {
                "id": 44,
                "key": "download-music-z-aaa-bbb",
                "key_fa": "دانلود-آهنگ-z-aaa-bbb",
                "type": {
                "key": 1,
                "value": "Mp3"
                },
                "name": "aaa bbb",
                "name_fa": "aaa bbb",
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
                "view": 4,
                "view_fa": 0,
                "view_app": 4,
                "play": 0,
                "play_fa": 0,
                "play_app": 0,
                "time": "",
                "directory": "z",
                "dl_link": "z - aaa bbb",
                "image": "z - aaa bbb.jpg",
                "key_pure": "z-aaa-bbb",
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
                "320": "http://localhost/navar/backend/web/upload/z/mp3/z - aaa bbb[320].mp3",
                "128": "http://localhost/navar/backend/web/upload/z/mp3/z - aaa bbb[128].mp3"
                }
            }
        ]
    }
     *
     *
     *
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 422 Unprocessable entity
     *     {
     *       "gender": [
     *          "Gender نمی‌تواند خالی باشد."
     *       ]
     *     }
     *
     * @apiError (Error 401)Unauthorized Login required
     *
     * @apiError (Error 404)NotFound Type required
     */

    public function run($id) {

        return (new ArtistPage)->search($id,Yii::$app->request->headers->get('type'));

    }

}
