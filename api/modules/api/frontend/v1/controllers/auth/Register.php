<?php

namespace api\modules\api\frontend\v1\controllers\auth;

use common\models\user\User;
use yii\base\Action;
use api\modules\api\frontend\v1\models\auth\RegisterForm;
use Yii;
use api\components\ControllerStatus;

class Register extends Action {
    
    use ControllerStatus;
    
    /**
     * @api {post} /auth/register Register user
     * @apiVersion 1.0.0
     * @apiName AuthRegister
     * @apiGroup Auth
     *
     * @apiParam (Data) {String} mobile Mobile number
     * 
     * 
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *        "id": "13",
     *        "security_code": "zfrVrZ2UOpiHJP9_9AR72rn4bdkV0v9u57d4d7194a5629.84211804"
     *     }
     *
     * @apiError (Error 422) UnprocessableEntity Validation error
     * 
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 422 Unprocessable entity
     *     {
     *         "mobile": [
     *             "شماره موبایل صحیح نمی باشد و باید به این شکل ارسال شود:‌0936..."
     *         ]
     *     }
     */
    public function run() {

        $model = new RegisterForm();
        $model->load(Yii::$app->getRequest()->getBodyParams(), '');
        
        if ($model->validate() && $user = $model->register()) {

            //$user->sendActivationEmail();

            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'token' => $user->getJWT()
            ];


            //$user->sendActivationSms();
            //return $model->getDataAfterSave($user);
        }
        else
        {
            $this->statusValidation();
            return $model->getErrors();
        }
    }

}
