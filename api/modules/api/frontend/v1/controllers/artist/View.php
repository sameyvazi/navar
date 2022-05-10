<?php

namespace api\modules\api\frontend\v1\controllers\artist;

use yii\base\Action;
use Yii;

class View extends Action {
    
    /**
     * @api {get} /artists/:id Artist View
     * @apiVersion 1.0.0
     * @apiName ArtistView
     * @apiGroup Artist
     * 
     * @apiParam (Params) {String} id Artist identity code | Artist key
     *
     * 
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
        {
            "id": 1,
            "name": "saman",
            "name_fa": "سامان",
            "activity": "Singer",
            "image": "saman.jpg",
            "like": 0,
            "like_fa": 0,
            "like_app": 0,
            "status": 1,
            "status_fa": 1,
            "status_app": 1,
            "key": "saman",
            "key_fa": "سامان-عیوضی"
        }
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
