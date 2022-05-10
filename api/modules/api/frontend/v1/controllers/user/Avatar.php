<?php

namespace api\modules\api\frontend\v1\controllers\user;

use yii\base\Action;
use Yii;
use api\modules\api\frontend\v1\models\user\AvatarForm;

class Avatar extends Action {   
    
    use \api\components\ControllerStatus;
    
    /**
     * @api {post} /users/avatar Upload avatar
     * @apiVersion 1.0.0
     * @apiName UserAvatar
     * @apiGroup User
     *
     * @apiParam (Data) {String} file
     * 
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *
     * @apiError (Error 422) UnprocessableEntity Validation error
     * 
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 422 Unprocessable entity
     *     {
     *       "file": [
     *         "لطفاً یک فایل آپلود کنید."
     *       ]
     *     }
     * 
     * @apiError (Error 401)Unauthorized Login required
     */
    public function run() {
        
        $model = new AvatarForm();
        $model->load(Yii::$app->getRequest()->getBodyParams(), '');
        if ($url = $model->save(Yii::$app->getUser()->identity))
        {
            return [
                'url' => $url
            ];
        }
        
        $this->statusValidation();
        return $model->getErrors();
        
    }

}
