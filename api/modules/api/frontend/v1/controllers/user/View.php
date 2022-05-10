<?php

namespace api\modules\api\frontend\v1\controllers\user;

use yii\base\Action;
use Yii;

class View extends Action {
    
    /**
     * @api {get} /users User own information
     * @apiVersion 1.0.0
     * @apiName UserOwnView
     * @apiGroup User
     * 
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     * 
     * @apiError (Error 401)Unauthorized Login required
     */
    public function run() {
        
        return Yii::$app->getUser()->identity;
    }

}
