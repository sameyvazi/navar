<?php

namespace api\modules\api\frontend\v1\models\contact;

use common\models\comment\Comment;
use common\models\contact\Contact;
use Yii;
use yii\base\Model;

class ContactCreateForm extends Model {

    public $author_name;
    public $author_email;
    public $content;
    public $subject;

    
     /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['author_email', 'author_name', 'content'], 'required'],
            ['subject', 'string'],
            ['author_email', 'email'],
        ];
    }

    public function attributeLabels() {
        return [
            'author_name' => Yii::t('app', 'Author Name'),
            'author_email' => Yii::t('app', 'Author Email'),
            'content' => Yii::t('app', 'Content'),
            'subject' => Yii::t('app', 'Subject'),
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

        $model = new Contact();
        $model->type = $type;
        $model->author_name = $this->author_name;
        $model->author_email = $this->author_email;
        $model->content = $this->content;
        $model->subject = $this->subject;
        $model->status = Comment::STATUS_DISABLED;
        $model->created_at = time();
        $model->save();

        return true;
    }
}