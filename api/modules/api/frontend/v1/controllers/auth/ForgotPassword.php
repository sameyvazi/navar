<?php

namespace api\modules\api\frontend\v1\controllers\auth;

use api\modules\api\frontend\v1\models\auth\ForgotPasswordForm;
use yii\base\Action;
use api\components\ControllerStatus;
use api\modules\api\frontend\v1\models\auth\ActivateForm;
use Yii;

class ForgotPassword extends Action {

    use ControllerStatus;
    
    /**
     * @api {post} /auth/forgot-password Forgot Password
     * @apiVersion 1.0.0
     * @apiName AuthForgotPassword
     * @apiGroup Auth
     *
     * @apiParam (Data) {Integer} code digit code for activation
     * @apiParam (Data) {String} security_code Security code
     * @apiParam (Data) {String} fcm FCM token [optional]
     * 
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 201 Created
     *
     * @apiError (Error 422) UnprocessableEntity Validation error
     * 
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 422 Unprocessable entity
     *      {
     *         "code": [
     *            "کد نمی‌تواند خالی باشد."
     *         ]
     *      }
     */
    public function run() {
        
        $model = new ForgotPasswordForm();
        
        $model->load(Yii::$app->getRequest()->getBodyParams(), '');
        
        if ($model->validate() && ($user = $model->forgot())) {

            $user->sendForgotPasswordEmail();
            $this->statusCreated();
            return true;
        }
        else
        {
            $this->statusValidation();
            return $model->getErrors();
        }
    }

}
