<?php

namespace api\modules\api\frontend\v1\controllers\playlist;

use yii\base\Action;
use Yii;

class View extends Action {
    
    /**
     * @api {get} /playlists/:id Playlist View
     * @apiVersion 1.0.0
     * @apiName PlaylistView
     * @apiGroup Playlist
     * 
     * @apiParam (Params) {Integer} id Playlist identity code
     *
     * 
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
    [
        {
        "id": 1,
        "no": 0,
        "music": {
            "id": 1,
            "key": "download-music-saman-test-test",
            "key_fa": "دانلود-آهنگ-saman-test-test",
            "type": {
            "key": 1,
            "value": "Mp3"
            },
            "name": "test",
            "name_fa": "test",
            "artist_id": 1,
            "special": null,
            "music_id": null,
            "music_no": 0,
            "lyric": "فرهنگ پرادوکسیکال در عصر ابتذال \r\nبا یک ملت خوشحال فدای تو یا زهراب\r\nصب کله پاچه با دار گشنه پا سفرهى افطار \r\nسلفی با کودک کار فدای تو یا زهراب \r\nزامبیای بازاری تنفس اجباری \r\nکارگر انتحاری فدای تو یا زهراب\r\nفاشیسم آمریکایی اسپرم آریایی \r\nعرق به جای چایی فدای تو زهراب\r\nفدای تو زندگى و وجود و عمر و جان ما\r\nسگ کوی تبربت و تویی تو آب و نان ما\r\nفدای تار مو تو همه کس وشعور ما\r\nتویى تو ثروت و سیاست و همه غرور ما\r\nزهراب زهراب زهراب زهراب زهراب ",
            "note": "",
            "note_fa": "",
            "note_app": "",
            "like": 10,
            "like_fa": 0,
            "like_app": 23,
            "view": 22,
            "view_fa": 0,
            "view_app": 27,
            "play": 3,
            "play_fa": 0,
            "play_app": 1,
            "time": "",
            "directory": "saman",
            "dl_link": "saman - test",
            "image": "saman - test.jpg",
            "key_pure": "test",
            "artist": {
            "id": 1,
            "name": "saman",
            "name_fa": "saman",
            "activity": "Singer",
            "image": "saman.jpg",
            "like": 0,
            "like_fa": 2,
            "like_app": 0,
            "status": 1,
            "status_fa": 1,
            "status_app": 1,
            "key": "saman",
            "key_fa": "saman",
            "link": "/f1/artists/saman"
            },
            "link": {
            "320": "http://localhost/navar/backend/web/upload/saman/mp3/saman - test[320].mp3",
            "128": "http://localhost/navar/backend/web/upload/saman/mp3/saman - test[128].mp3"
            }
        },
        "playlist": {
            "id": 3,
            "name": "sport",
            "name_fa": "sport",
            "image": "5991c38993884.jpg",
            "no": 0,
            "limit": 5,
            "public": 1,
            "mood": {
                "id": 2,
                "name": "acasc",
                "name_fa": "cacsdc",
                "image": "59903cd3cd11f.jpg",
                "no": 0
                }
            }
        }
    ]
     *
     *
     * @apiError (Error 401)Unauthorized Login required
     *
     * @apiError (Error 404)NotFound Type required
     */
    public function run($id) {

        return $this->controller->findModel($id, Yii::$app->request->headers->get('type'));

    }

}
