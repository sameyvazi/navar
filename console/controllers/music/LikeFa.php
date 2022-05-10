<?php

namespace console\controllers\music;

use common\models\like\Like as Likes;
use common\models\music\Music;
use common\models\tag\Tag;
use yii\helpers\Console;
use yii\base\Action;
use Yii;

class LikeFa extends Action {

    public function run() {

        $startOp = $this->controller->prompt('Are you sure start this function? ["yes" or "no"]', [
            'required' => true,
        ]);

        if($startOp !== "yes")
            die;

        Yii::$app->language = 'en';
        $this->controller->stdout("Transfer of LikeFa started...\n", Console::FG_YELLOW);

        $likes = Yii::$app->temp->createCommand("SELECT * FROM tbl_likes")->queryAll();
        foreach ($likes as $like){

            $params = [':id' => $like['p_id']];
            $meta = Yii::$app->temp->createCommand("SELECT * FROM tbl_meta_key WHERE id=:id", $params)->queryOne();

            if ($meta){

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

                $music = Music::find()->select(['type', 'key_pure', 'id'])->where(['type' => $type, 'key_pure' => $meta['key']])->one();


                $model = Likes::find()->where(['type' => Music::TYPE_MUSICPLUS ,'post_id' => isset($music->id) ? $music->id : 0, 'author_ip' => $like['ip']])->one();

                if (!$model){
                    $model = new Likes();
                }

                $model->type = Music::TYPE_MUSICPLUS;
                $model->post_id = isset($music->id) ? $music->id : 0;
                $model->author_ip = $like['ip'];
                $model->user_id = 0;
                $model->created_at = strtotime($like['ip_date']);
                $model->post_type = Tag::TYPE_MUSIC;
                $model->save();
            }
        }

        $this->controller->stdout("The transfer of the LikeFa is over.\n", Console::FG_GREEN);
        
    }

}
