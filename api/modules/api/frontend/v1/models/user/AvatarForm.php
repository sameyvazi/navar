<?php

namespace api\modules\api\frontend\v1\models\user;

use Yii;
use yii\base\Model;
use yii\web\UploadedFile;

class AvatarForm extends Model {
    
    public $file;
        
     /**
     * @inheritdoc
     */
    public function rules() {
        return [
            ['file', 'file', 'extensions' => ['jpg', 'png'], 'mimeTypes' => 'image/*', 'skipOnEmpty' => false],
        ];
    }
    
    public function attributeLabels() {
        return [
            'file' => Yii::t('app', 'Image'),
        ];
    }
    
    public function save($model)
    {
        
        $this->file = UploadedFile::getInstanceByName('file');
        
        if (!$this->validate())
        {
            return false;
        }
        
        $model->uploadAvatar($this->file);
        
        return $model->save() ? $model->getAvatarUrl($model->avatar) : false;

    }
}