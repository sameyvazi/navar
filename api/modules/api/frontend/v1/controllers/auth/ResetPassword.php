<?php

namespace api\modules\api\frontend\v1\controllers\auth;

use api\modules\api\frontend\v1\models\auth\ForgotPasswordForm;
use api\modules\api\frontend\v1\models\auth\ResetPasswordForm;
use yii\base\Action;
use api\components\ControllerStatus;
use api\modules\api\frontend\v1\models\auth\ActivateForm;
use Yii;

class ResetPassword extends Action {

    use ControllerStatus;
    
    /**
     * @api {post} /auth/reset-password/:id Reset Password
     * @apiVersion 1.0.0
     * @apiName AuthResetPassword
     * @apiGroup Auth
     *
     * @apiParam (Params) {String} id identity of user
     *
     * @apiParam (Data) {String} email user mail
     * @apiParam (Data) {Integer} code code
     * @apiParam (Data) {String} password New Password
     *
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
        
        $model = new ResetPasswordForm();
        
        $model->load(Yii::$app->getRequest()->getBodyParams(), '');
        
        if ($model->validate() && ($user = $model->reset())) {

            return $user;
        }
        else
        {
            $this->statusValidation();
            return $model->getErrors();
        }
    }

}
