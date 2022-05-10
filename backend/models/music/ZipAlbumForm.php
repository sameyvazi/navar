<?php

namespace backend\models\music;

use common\models\music\Music;
use yii\base\Model;


class ZipAlbumForm extends Model
{
    
    public $id;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'required'],
            [['id'], 'integer'],
        ];
    }
    
    public function zip($validate = true)
    {

        if ($validate && !$this->validate()) {
            return false;
        }

        //var_dump('cd '.\Yii::getAlias('@app').' && cd .. && php yii album/zip '.$this->id);die;

        pclose(popen('cd '.\Yii::getAlias('@app').' && cd .. && php yii album/zip '.$this->id, 'r'));

        return true;



//        if (!$model->save(false))
//        {
//
//            throw new ServerErrorHttpException('Failed to create the object for unknown reason.');
//        }else{
//
//
//
//        }

            //return $model;
    }

}
