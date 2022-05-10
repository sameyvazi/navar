<?php

namespace console\controllers\music;

use common\models\music\Music;
use yii\helpers\Console;
use yii\base\Action;
use Yii;
use common\models\comment\Comment as Comments;

class Comment extends Action {

    public function run() {

        $startOp = $this->controller->prompt('Are you sure start this function? ["yes" or "no"]', [
            'required' => true,
        ]);

        if($startOp !== "yes")
            die;

        Yii::$app->language = 'en';
        $this->controller->stdout("Transfer of Comments started...\n", Console::FG_YELLOW);

        $comments = Yii::$app->temp->createCommand("SELECT * FROM tbl_comment")->queryAll();

        Yii::$app->db->createCommand()->truncateTable(Comments::tableName())->execute();

        foreach ($comments as $comment){

            $params = [':id' => $comment['meta_id']];
            $meta = Yii::$app->temp->createCommand("SELECT * FROM tbl_meta_key WHERE id=:id", $params)->queryOne();



            switch ($meta['model']){
                case 'mp3':
                    $type = Music::TYPE_MP3;
                    break;
                case 'video':
                    $type = Music::TYPE_VIDEO;
                    $meta['key'] = $meta['key'].'-video';
                    break;
                case 'album':
                    $type = Music::TYPE_ALBUM;
                    $meta['key'] = $meta['key'].'-album';
                    break;
            }

            $music = Music::find()->where(['key_pure' => $meta['key'], 'type' => $type])->one();

            $model = new Comments();

            $model->post_id = isset($music->id) ? $music->id : 0;
            $model->type = Music::TYPE_INAVAR;
            $model->author_name = $comment['name'];
            $model->author_email = $comment['email'];
            $model->author_ip = '::1';
            $model->content = $comment['content'];
            $model->like = 0;
            $model->parent_comment_id = 0;
            $model->status = $comment['status'] == 1 ? Comments::STATUS_ACTIVE : Comments::STATUS_DISABLED;
            $model->created_at = strtotime($comment['release_date']);
            $model->updated_at = strtotime($comment['release_date']);
            $model->save();

        }

        $this->controller->stdout("The transfer of the Comments is over.\n", Console::FG_GREEN);
        
    }

}
