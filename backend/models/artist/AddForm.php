<?php

namespace backend\models\artist;

use common\models\admin\Admin;
use common\models\artist\Artist;
use common\models\tag\Tag;
use common\models\tag\TagRelation;
use yii\base\Model;
use Yii;
use yii\helpers\FileHelper;
use yii\imagine\Image;
use yii\web\ServerErrorHttpException;
use yii\web\UploadedFile;
use yii2mod\ftp\FtpClient;

class AddForm extends Model
{
    
    public $name;
    public $nameFa;
    public $key;
    public $keyFa;
    public $activity;
    public $image;
    public $status;
    public $statusFa;
    public $statusApp;
    public $statusSite;
    public $like;
    public $likeFa;
    public $likeApp;
    public $tag;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'nameFa', 'activity', 'status', 'statusFa', 'statusApp', 'statusSite', 'key', 'keyFa'], 'required'],
            [['activity', 'like', 'likeFa', 'likeApp', 'status', 'statusFa', 'statusApp', 'statusSite'], 'integer'],
            [['name', 'nameFa'], 'string', 'max' => 255],
            ['tag', 'safe'],
            [['image'], 'file', 'skipOnEmpty' => true, 'extensions' => 'jpg, png', 'mimeTypes' => 'image/jpeg, image/png',],
            //['key', 'in', 'range'=>['a','b'], 'allowArray' => true],
            [['key'], 'unique', 'targetClass' => 'common\models\artist\Artist' , 'targetAttribute'=>'key'],
            [['keyFa'], 'unique', 'targetClass' => 'common\models\artist\Artist' , 'targetAttribute'=>'key_fa'],
        ];
    }


    public function attributeLabels()
    {
        return [

        ];
    }

    public function key($attribute, $params)
    {
        if (!$this->hasErrors()) {

            $artist = Artist::find()->where(['key' => $this->$attribute])->one();

            if (isset($artist)) {
                $this->addError($attribute, 'Incorrect username or password.');
            }
        }
    }

    /**
     * Signs user up.
     *
     * @return Boolean|User
     */
    public function add($validate = true)
    {
        if ($validate && !$this->validate())
        {
            return false;
        }

        $model = new Artist();

        $model->name = $this->name;
        $model->name_fa = $this->nameFa;
        $model->key = str_replace(' ', '-', strtolower($this->key));
        $model->key_fa = str_replace(' ', '-', $this->keyFa);
        $model->activity = $this->activity;
        $model->status = $this->status;
        $model->status_fa = $this->statusFa;
        $model->status_app = $this->statusApp;
        $model->status_site = $this->statusSite;
        $model->like = $this->like;
        $model->like_fa = $this->likeFa;
        $model->like_app = $this->likeApp;
        $model->image = $model->key.'.jpg';

        if (!$model->save(false))
        {
            throw new ServerErrorHttpException('Failed to create the object for unknown reason.');
        }else{

            $this->createDirectory(Yii::$app->params['uploadUrl'].$model->key, 0775,true);
            $this->createDirectory(Yii::$app->params['uploadUrl'].$model->key . '/mp3', 0775, true);
            $this->createDirectory(Yii::$app->params['uploadUrl'].$model->key . '/video', 0775, true);
            $this->createDirectory(Yii::$app->params['uploadUrl'].$model->key . '/cover', 0775, true);


            //ftp
            $ftp = Yii::$app->helper->ftpLogin();
            $ftp->mkdir($model->key);
            $ftp->mkdir($model->key . '/mp3');
            $ftp->mkdir($model->key . '/video');
            $ftp->mkdir($model->key . '/cover');


            $tags[] = str_replace(' ', '-', $model->name);
            $tags[] = str_replace(' ', '-', $model->name_fa);
            $tags[] = $model->key;
            $tags[] = $model->key_fa;

            if ($model->activity == Artist::TYPE_SINGER){
                $tags[] = 'دانلود-فول-آلبوم-'.str_replace(' ', '-', $model->name_fa);
            }


            if ($this->tag != ""){
                $tagCustom = explode("\n", $this->tag);

                foreach ($tagCustom as $t){
                    $tags[] = $t;
                }
            }

            \Yii::$app->tags->hashtag($tags, Tag::TYPE_ARTIST, $model->id);

        }
        return $model;
    }

    protected function createDirectory($address, $mode, $recursive){

        FileHelper::createDirectory($address, $mode, $recursive);
        return;

    }

}
