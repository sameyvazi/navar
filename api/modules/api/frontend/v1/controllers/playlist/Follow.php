<?php

namespace api\modules\api\frontend\v1\controllers\playlist;

use api\modules\api\frontend\v1\models\playlist\PlaylistFollow;
use yii\base\Action;

class Follow extends Action {
    
    /**
     * @api {get} /playlists/follow/:id Playlist Follow
     * @apiVersion 1.0.0
     * @apiName PlaylistFollow
     * @apiGroup Playlist
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

        return (new PlaylistFollow)->follow($id);
    }

}
