<?php

namespace api\modules\api\frontend\v1\controllers\artist;

use yii\base\Action;
use Yii;
use api\modules\api\frontend\v1\models\artist\ArtistSearch;

class Index extends Action {

    /**
     * @api {get} /artists Artist List
     * @apiVersion 1.0.0
     * @apiName ArtistIndex
     * @apiGroup Artist
     *
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *
     *     [
                {
                    "id": 1,
                    "name": "ffff",
                    "name_fa": "ffff",
                    "key": "ffffaa",
                    "key_fa": "aaaffff",
                    "activity": "Singer",
                    "like": 0,
                    "like_fa": 0,
                    "like_app": 0,
                    "status": 1,
                    "status_fa": 1,
                    "status_app": 1,
                    "link": "/f1/artists/ffffaa"
                },
                {
                    "id": 2,
                    "name": "ffff",
                    "name_fa": "ffff",
                    "key": "ffff",
                    "key_fa": "ffff",
                    "activity": "Singer",
                    "like": 0,
                    "like_fa": 0,
                    "like_app": 0,
                    "status": 1,
                    "status_fa": 1,
                    "status_app": 1,
                    "link": "/f1/artists/ffff"
                }
        ]
     *
     *
     * @apiError (Error 401)Unauthorized Login required
     *
     * @apiError (Error 404)NotFound Type required
     */

    public function run() {

        return (new ArtistSearch)->search(Yii::$app->getRequest()->get());

    }

}
