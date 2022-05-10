<?php

namespace api\modules\api\frontend\v1\controllers\playlist;

use common\models\playlist\PlaylistFollower;
use yii\base\Action;
use Yii;

class UserPlaylist extends Action {
    
    /**
     * @api {get} /playlists/user-playlist User Playlist
     * @apiVersion 1.0.0
     * @apiName PlaylistUser
     * @apiGroup Playlist
     *
     *
     * 
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
    [
        {
            "id": 1,
            "user_id": 1,
            "no": 0,
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
    public function run() {

        return PlaylistFollower::find()->where(['user_id' => Yii::$app->user->id])->orderBy(['no' => SORT_ASC])->all();

    }

}
