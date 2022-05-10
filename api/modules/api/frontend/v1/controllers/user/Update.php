<?php

namespace api\modules\api\frontend\v1\controllers\user;

use yii\base\Action;
use Yii;
use api\components\ControllerStatus;
use api\modules\api\frontend\v1\models\user\UpdateForm;

class Update extends Action {
    
    use ControllerStatus;
    
    /**
     * @api {put} /users Update user info
     * @apiVersion 1.0.0
     * @apiName UserUpdate
     * @apiGroup User
     *
     * @apiParam (Data) {String} username
     * @apiParam (Data) {String} email
     * @apiParam (Data) {String} mobile
     * 
     * 
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *
     * @apiError (Error 422) UnprocessableEntity Validation error
     * 
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 422 Unprocessable entity
     *     {
     *       "gender": [
     *          "Gender نمی‌تواند خالی باشد."
     *       ]
     *     }
     * 
     * @apiError (Error 401)Unauthorized Login required
     */
    public function run() {
        
        $model = new UpdateForm();
        
        $model->load(Yii::$app->getRequest()->getBodyParams(), '');
        
        if ($user = $model->save(Yii::$app->getUser()->identity))
        {
            return $user;
        }
        else
        {
            $this->statusValidation();
            return $model->getErrors();
        }
    }

}
