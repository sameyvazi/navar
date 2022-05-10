<?php

namespace api\modules\api\frontend\v1\controllers\comment;

use yii\base\Action;
use Yii;

class View extends Action {
    
    /**
     * @api {get} /comments/:post_id Comments List
     * @apiVersion 1.0.0
     * @apiName CommentList
     * @apiGroup Comment
     * 
     * @apiParam (Params) {Integer} post_id post identity code
     *
     * 
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
    [
        {
            "id": 1,
            "post_id": 1,
            "author_name": "saman",
            "author_email": null,
            "content": "hvjjbjbkbkkjb kjbkbk \njhv bbjbb jbuguyugugubu bilnln",
            "like": 0,
            "created_at": 1502296437,
            "reply": [
                {
                    "id": 5,
                    "post_id": 1,
                    "type": 1,
                    "author_name": "saman",
                    "author_email": "saman@gmail.com",
                    "author_ip": "::1",
                    "content": "hvjjbjbkbkkjb kjbkbk \njhv bbjbb jbuguyugugubu bilnln",
                    "like": 0,
                    "parent_comment_id": 1,
                    "status": 1,
                    "created_at": 1502298593,
                    "updated_at": 1502298730
                },
                {
                    "id": 6,
                    "post_id": 1,
                    "type": 1,
                    "author_name": "saman",
                    "author_email": "saman@gmail.com",
                    "author_ip": "::1",
                    "content": "hvjjbjbkbkkjb kjbkbk \njhv bbjbb jbuguyugugubu bilnln",
                    "like": 0,
                    "parent_comment_id": 1,
                    "status": 1,
                    "created_at": 1502300305,
                    "updated_at": 1502659348
                }
            ]
        },
        {
            "id": 2,
            "post_id": 1,
            "author_name": "saman",
            "author_email": "saman@gmail.com",
            "content": "hvjjbjbkbkkjb kjbkbk \njhv bbjbb jbuguyugugubu bilnln",
            "like": 0,
            "created_at": 1502296512,
            "reply": false
        },
        {
            "id": 3,
            "post_id": 1,
            "author_name": "saman",
            "author_email": "saman@gmail.com",
            "content": "hvjjbjbkbkkjb kjbkbk \njhv bbjbb jbuguyugugubu bilnln",
            "like": 0,
            "created_at": 1502296607,
            "reply": false
        },
        {
            "id": 4,
            "post_id": 1,
            "author_name": "saman",
            "author_email": "saman@gmail.com",
            "content": "hvjjbjbkbkkjb kjbkbk \njhv bbjbb jbuguyugugubu bilnln",
            "like": 0,
            "created_at": 1502298589,
            "reply": false
        }
    ]
     *
     *
     * @apiError (Error 401)Unauthorized Login required
     *
     * @apiError (Error 404)NotFound Type required
     */
    public function run($id) {

        $type = Yii::$app->request->headers->get('type');
        if ($type == 4){
            $type = 3;
        }

        $comments = $this->controller->findModel($id, $type);
        $newComments = [];

        foreach ($comments as $key => $comment){


            if($comment->parent_comment_id == 0){
                $new = [
                    'id' => $comment->id,
                    'post_id' => $comment->post_id,
                    'author_name' => $comment->author_name,
                    'author_email' => $comment->author_email,
                    'content' => $comment->content,
                    'like' => $comment->like,
                    'created_at' => $comment->created_at,
                    'reply' => false
                ];

                $newComments[$key] = $new;
            }

            if($comment->parent_comment_id != 0){
                $newCommentsReply[$comment->parent_comment_id][] = $comment;
            }
        }

        foreach ($newComments as $key => $cm){

            if (isset($newCommentsReply[$cm['id']])){

                $newComments[$key]['reply'] =  $newCommentsReply[$cm['id']];
            }
        }

        return $newComments;

    }

}
