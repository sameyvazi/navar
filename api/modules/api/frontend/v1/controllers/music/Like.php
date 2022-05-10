<?php

namespace api\modules\api\frontend\v1\controllers\music;

use api\modules\api\frontend\v1\models\music\MusicLike;
use yii\base\Action;

class Like extends Action {
    
    /**
     * @api {get} /musics/like/:id Music Like
     * @apiVersion 1.0.0
     * @apiName MusicLike
     * @apiGroup Music
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

        return (new MusicLike)->like($id);
    }

}
