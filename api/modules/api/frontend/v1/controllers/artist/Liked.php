<?php

namespace api\modules\api\frontend\v1\controllers\artist;

use api\modules\api\frontend\v1\models\artist\ArtistLike;
use yii\base\Action;
use common\models\tag\Tag;
use Yii;

class Liked extends Action {
    
    /**
     * @api {get} /artists/liked/:id Artist Like
     * @apiVersion 1.0.0
     * @apiName ArtistLike
     * @apiGroup Artist
     * 
     * @apiParam (Params) {String} id Artist identity code
     *
     * 
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
    {
        "id": 1,
        "name": "saman",
        "name_fa": "saman",
        "activity": "Singer",
        "image": "saman.jpg",
        "like": 0,
        "like_fa": 2,
        "like_app": 0,
        "status": 1,
        "status_fa": 1,
        "status_app": 1,
        "key": "saman",
        "key_fa": "saman",
        "link": "/f1/artists/saman"
    }
     *
     *
     * @apiError (Error 401)Unauthorized Login required
     *
     * @apiError (Error 404)NotFound Type required
     *
     *
     * @apiError (Error 400) BadRequest Bad Request
     *
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 400 Bad Request
        {
            "name": "Bad Request",
            "message": "This artist before liked!",
            "code": 0,
            "status": 400,
            "type": "yii\\web\\BadRequestHttpException"
        }
     *
     *
     */
    public function run($id) {

        $type = \Yii::$app->request->headers->get('type');
        if (\common\models\like\Like::find()->where([
            'type' => $type,
            'post_id' => $id,
            'author_ip' => Yii::$app->ip->getUserIp(),
            'post_type' => Tag::TYPE_ARTIST,
            'user_id' => isset(Yii::$app->user->id) ? Yii::$app->user->id : 0
        ])->one()){

            return true;
        }

        return false;
    }

}
