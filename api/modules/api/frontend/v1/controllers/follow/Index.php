<?php

namespace api\modules\api\frontend\v1\controllers\follow;

use api\modules\api\frontend\v1\models\follow\FollowSearch;
use yii\base\Action;
use Yii;

class Index extends Action {

    /**
     * @api {get} /follows Follow List
     * @apiVersion 1.0.0
     * @apiName FollowIndex
     * @apiGroup Follow
     *
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *
    [
        {
            "id": 2,
            "post_id": {
                "id": 1,
                "name": "saman",
                "name_fa": "سامان",
                "activity": "Singer",
                "image": "saman.jpg",
                "like": 2,
                "like_fa": 0,
                "like_app": 0,
                "status": 0,
                "status_fa": 0,
                "status_app": 1,
                "key": "saman",
                "key_fa": "سامان",
                "link": "/f1/artists/saman"
            },
            "post_type": {
                "key": 2,
                "value": "Artist"
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

        return (new FollowSearch)->search(Yii::$app->getRequest()->get());

    }

}
