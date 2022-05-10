<?php

namespace api\modules\api\frontend\v1\controllers\music;

use common\models\tag\Tag;
use yii\base\Action;
use Yii;

class Liked extends Action {
    
    /**
     * @api {get} /musics/liked/:id Music Like
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

        $type = \Yii::$app->request->headers->get('type');

        if (\common\models\like\Like::find()->where([
            'type' => $type,
            'post_id' => $id,
            'post_type' => Tag::TYPE_MUSIC,
            'author_ip' => Yii::$app->ip->getUserIp(),
            'user_id' => isset(Yii::$app->user->id) ? Yii::$app->user->id : 0
        ])->one()){

            return true;
        }

        return false;
    }

}
