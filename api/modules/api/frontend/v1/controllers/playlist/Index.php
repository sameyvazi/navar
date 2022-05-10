<?php

namespace api\modules\api\frontend\v1\controllers\playlist;

use yii\base\Action;
use Yii;
use api\modules\api\frontend\v1\models\playlist\PlaylistSearch;

class Index extends Action {

    /**
     * @api {get} /playlists Playlist list
     * @apiVersion 1.0.0
     * @apiName PlaylistIndex
     * @apiGroup Playlist
     *
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *
    [
        {
            "id": 6,
            "name": "mnb",
            "name_fa": "mnb",
            "image": "599313bc9920f.jpg",
            "no": 0,
            "limit": 5,
            "public": 1,
            "mood": {
                "id": 3,
                "name": "aaaa",
                "name_fa": "aaaa",
                "image": "59901e9a77305.jpg",
                "no": 0
            }
        }
    ]
     *
     *
     * @apiError (Error 401)Unauthorized Login required
     *
     * @apiError (Error 404)NotFound Type required
     */

    public function run() {

        return (new PlaylistSearch)->search(Yii::$app->getRequest()->get());

    }

}
