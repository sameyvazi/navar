<?php

namespace api\modules\api\frontend\v1\controllers\artist;

use api\modules\api\frontend\v1\models\artist\ArtistFollow;
use yii\base\Action;

class Follow extends Action {
    
    /**
     * @api {get} /artists/follow/:id Artist Follow
     * @apiVersion 1.0.0
     * @apiName ArtistFollow
     * @apiGroup Artist
     * 
     * @apiParam (Params) {String} id Music identity code
     *
     * 
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *
     *
     * @apiError (Error 401)Unauthorized Login required
     *
     * @apiError (Error 404)NotFound Type required
     */
    public function run($id) {

        return (new ArtistFollow)->follow($id);
    }

}
