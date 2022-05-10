<?php

namespace api\modules\api\frontend\v1\controllers\auth;

use yii\base\Action;
use api\components\ControllerStatus;
use api\modules\api\frontend\v1\models\auth\ActivateForm;
use Yii;

class Activate extends Action {

    use ControllerStatus;
    
    /**
     * @api {post} /auth/activate/:id Activate user login
     * @apiVersion 1.0.0
     * @apiName AuthActivate
     * @apiGroup Auth
     * 
     * @apiParam (Params) {String} id identity of user
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
    public function run($id) {
        
        $model = new ActivateForm();
        
        $model->load(Yii::$app->getRequest()->getBodyParams(), '');
        $model->id = $id;
        
        if ($model->validate() && ($user = $model->activate())) {
            $this->statusCreated();
            return $model->getDataAfterSave($user);
        }
        else
        {
            $this->statusValidation();
            return $model->getErrors();
        }
    }

}
