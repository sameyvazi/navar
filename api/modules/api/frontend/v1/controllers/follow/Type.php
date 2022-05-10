<?php

namespace api\modules\api\frontend\v1\controllers\follow;

use common\models\follower\Follower;
use yii\base\Action;
use common\models\music\Music;

class Type extends Action {
    
    /**
     * @api {get} /follows/type Follow Type
     * @apiVersion 1.0.0
     * @apiName FollowType
     * @apiGroup Follow
     *
     * 
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
    [
        {
            "key": 1,
            "value": "Mp3"
        },
        {
            "key": 2,
            "value": "Video"
        },
        {
            "key": 3,
            "value": "Album"
        }
    ]
     *
     *
     * @apiError (Error 401)Unauthorized Login required
     */
    public function run() {

        $types = Follower::getTypeList();

        foreach ($types as $key => $value){
            $type[] = [
                'key' => $key,
                'value' => $value
            ];
        }
        return $type;

    }

}
