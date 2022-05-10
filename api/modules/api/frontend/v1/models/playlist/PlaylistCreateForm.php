<?php

namespace api\modules\api\frontend\v1\models\playlist;

use common\models\comment\Comment;
use common\models\mood\Mood;
use common\models\playlist\Playlist;
use common\models\playlist\PlaylistFollower;
use Yii;
use yii\base\Model;

class PlaylistCreateForm extends Model {
    
    public $name;
    public $image;
    public $no;

    
     /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'length' => [4, 30]],
            [['no'], 'safe']
        ];
    }

    public function attributeLabels() {
        return [
            'name' => Yii::t('app', 'Name'),
            'mood_id' => Yii::t('app', 'Mood ID'),
            'image' => Yii::t('app', 'Image'),
        ];
    }

    public function add(){

        $type = Yii::$app->request->headers->get('type');
        if (is_null($type)) {
            throw new \yii\web\NotFoundHttpException(\Yii::t('app', 'Send the client type in the header!'));
        }

        $model = new Playlist();
        $model->name = $this->name;
        $model->name_fa = $this->name;
        $model->mood_id = Yii::$app->params['moodIdUsers'];
        $model->image = 'default.jpg';
        $model->no = ($this->no != null) ? $this->no : 0;
        $model->limit = Yii::$app->params['playlistLimit'];
        $model->status = Playlist::STATUS_ACTIVE;
        $model->status_fa = Playlist::STATUS_ACTIVE;
        $model->status_app = Playlist::STATUS_ACTIVE;
        $model->user_id = Yii::$app->user->id;
        $model->created_at = time();
        $model->updated_at = time();
        $model->public = Playlist::TYPE_PRIVATE;
        $model->save();

        $modelFollow = new PlaylistFollower();
        $modelFollow->playlist_id = $model->id;
        $modelFollow->user_id = $model->user_id;
        $modelFollow->no = $model->no;
        $modelFollow->save();

        return true;
    }
}