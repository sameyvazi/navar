<?php

namespace api\modules\api\frontend\v1\controllers\user;

use yii\base\Action;
use Yii;

class AvatarDelete extends Action {   
    
    /**
     * @api {delete} /users/avatar Delete avatar
     * @apiVersion 1.0.0
     * @apiName UserAvatarDelete
     * @apiGroup User
     * 
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *       "deleted": 1
     *     }
     * 
     * @apiError (Error 401)Unauthorized Login required
     */
    public function run() {
        
        return [
            'deleted' => Yii::$app->getUser()->getIdentity()->deleteAvatar()
        ];
        
    }

}
