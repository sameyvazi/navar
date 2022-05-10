<?php

namespace api\modules\api\frontend\v1\models\comment;

use common\models\comment\Comment;
use Yii;
use yii\base\Model;

class CommentCreateForm extends Model {
    
    public $post_id;
    public $author_name;
    public $author_email;
    public $content;
    public $parent_comment_id;

    
     /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['post_id', 'author_name', 'content'], 'required'],
            ['author_email', 'email'],
            [['post_id', 'parent_comment_id'], 'number', 'integerOnly' => true],
        ];
    }

    public function attributeLabels() {
        return [
            'post_id' => Yii::t('app', 'Post ID'),
            'author_name' => Yii::t('app', 'Author Name'),
            'author_email' => Yii::t('app', 'Author Email'),
            'content' => Yii::t('app', 'Content'),
            'parent_comment_id' => Yii::t('app', 'Parent Comment ID'),
        ];
    }

    public function add(){


        $type = Yii::$app->request->headers->get('type');
        if ($type == 4){
            $type = 3;
        }

        if (is_null($type)) {
            throw new \yii\web\NotFoundHttpException(\Yii::t('app', 'Send the client type in the header!'));
        }

        $model = new Comment();
        $model->post_id = $this->post_id;
        $model->type = $type;
        $model->author_name = $this->author_name;
        $model->author_email = $this->author_email;
        $model->author_ip = Yii::$app->ip->getUserIp();
        $model->content = $this->content;
        $model->parent_comment_id = isset($this->parent_comment_id) ? $this->parent_comment_id : 0;
        $model->status = Comment::STATUS_DISABLED;
        $model->created_at = time();
        $model->updated_at = time();
        $model->save();

        return true;
    }
}