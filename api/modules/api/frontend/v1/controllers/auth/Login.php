<?php

namespace api\modules\api\frontend\v1\controllers\auth;

use common\models\user\User;
use yii\base\Action;
use api\modules\api\frontend\v1\models\auth\LoginForm;
use Yii;
use api\components\ControllerStatus;

class Login extends Action {
    
    use ControllerStatus;
    
    /**
     * @api {post} /auth/login Login user
     * @apiVersion 1.0.0
     * @apiName AuthLogin
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
     *             "شماره موبایل صحیح نمی باشد و باید به این شکل ارسال شود:‌09364986541"
     *         ]
     *     }
     */
    public function run() {
        
        $model = new LoginForm();
        $model->load(Yii::$app->getRequest()->getBodyParams(), '');
        
        if ($model->validate() && $user = $model->login()) {

            return [
                'token' => $user->getJWT(),
                'user' => User::find()->where(['id' => $user->id])->one(),
            ];
        }
        else
        {
            $this->statusValidation();
            return $model->getErrors();
        }
    }

}
