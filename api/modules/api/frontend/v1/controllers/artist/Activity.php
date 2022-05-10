<?php

namespace api\modules\api\frontend\v1\controllers\artist;

use common\models\artist\Artist;
use yii\base\Action;
use Yii;

class Activity extends Action {
    
    /**
     * @api {get} /artists/activity Artist Activities
     * @apiVersion 1.0.0
     * @apiName ArtistActivities
     * @apiGroup Artist
     *
     * 
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
    [
        {
            "key": 1,
            "value": "Singer"
        },
        {
            "key": 2,
            "value": "Composer"
        },
        {
            "key": 3,
            "value": "Lyric"
        }
    ]
     *
     *
     * @apiError (Error 401)Unauthorized Login required
     *
     * @apiError (Error 404)NotFound Type required
     */
    public function run() {

        $artists = Artist::getTypeList();

        $act[] = [
            'key' => 0,
            'value' => Yii::t('app', 'All activity')
        ];

        foreach ($artists as $key => $value){
            $act[] = [
                'key' => $key,
                'value' => $value
            ];
        }
        return $act;

    }

}
