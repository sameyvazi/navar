<?php

namespace api\modules\api\frontend\v1\models\playlist;

use common\models\comment\Comment;
use common\models\mood\Mood;
use common\models\playlist\Playlist;
use Yii;
use yii\base\Model;

class PlaylistUpdateForm extends Model {
    
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
            'image' => Yii::t('app', 'Image'),
        ];
    }

    public function save($id){

        $type = Yii::$app->request->headers->get('type');
        if (is_null($type)) {
            throw new \yii\web\NotFoundHttpException(\Yii::t('app', 'Send the client type in the header!'));
        }

        $model = Playlist::find()->where(['id' => $id, 'user_id' => Yii::$app->user->id, 'public' => Playlist::TYPE_PRIVATE])->one();

        if ($model !== null){
            $model->name = $this->name;
            $model->name_fa = $this->name;
            $model->no = isset($this->no) ? $this->no : 0;
            $model->updated_at = time();
            $model->save();

            return true;
        }else{
            throw new \yii\web\NotFoundHttpException(\Yii::t('app', 'The requested page does not exist.'));
        }

    }
}