<?php

namespace api\modules\api\frontend\v1\controllers\contact;

use api\modules\api\frontend\v1\models\contact\ContactCreateForm;
use yii\base\Action;
use Yii;
use api\modules\api\frontend\v1\models\comment\CommentCreateForm;

class Create extends Action {

    use \api\components\ControllerStatus;
    /**
     * @api {post} /comments Create Comment
     * @apiVersion 1.0.0
     * @apiName CommentCreate
     * @apiGroup Comment
     * 
     * @apiParam (Data) {Integer} post_id Music id
     * @apiParam (Data) {String} author_name author name
     * @apiParam (Data) {String} author_email author email
     * @apiParam (Data) {String} content content
     * @apiParam (Data) {String} parent_comment_id comment id [optional]
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 204 NoContent
     * 
     * @apiError (Error 422) UnprocessableEntity Validation error
     *
     * @apiError (Error 401)Unauthorized Login required
     *
     * @apiError (Error 404)NotFound Type required
     *
     *
     * @apiError (Error 422) UnprocessableEntity entity error
     *
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 422 Unprocessable entity
    {
        "post_id": [
            "Post ID نمی\u200cتواند خالی باشد."
        ],
        "author_name": [
            "Author Name نمی\u200cتواند خالی باشد."
        ],
        "author_email": [
            "Author Email نمی\u200cتواند خالی باشد."
        ],
        "content": [
            "Content نمی\u200cتواند خالی باشد."
        ]
    }
     */
    public function run() {
        
        $model = new ContactCreateForm();
        $model->load(Yii::$app->getRequest()->getBodyParams(), '');
        if ($model->validate() && $model->add())
        {

            return $this->statusCreated();
        }
        
        $this->statusValidation();
        return $model->getErrors();

    }
}
