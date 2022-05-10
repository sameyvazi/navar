<?php

namespace backend\models\artist;

use yii\base\Model;
use yii\web\UploadedFile;

class UploadForm extends Model
{
    /**
     * @var UploadedFile
     */
    public $imageFile;
    public $music1080;
    public $music720;
    public $music480;
    public $music320;
    public $music128;
    public $musicFile;

    public function rules()
    {
        return [
            //[['imageFile', 'music1080', 'music720', 'music480', 'music320', 'music128'], 'file'],
            [['imageFile', 'musicFile'], 'file'],
        ];
    }

    public function upload($address, $fileName)
    {
        if ($this->validate()) {

            if (isset($this->imageFile)){
                $name = $fileName;
                $this->imageFile->saveAs($address . $name);
                return true;
            }elseif (isset($this->music128)){
                $this->music128->saveAs($address . $fileName);
                return true;
            }elseif (isset($this->music320)){
                $this->music320->saveAs($address . $fileName);
                return true;
            }elseif (isset($this->music480)){
                $this->music480->saveAs($address . $fileName);
                return true;
            }elseif (isset($this->music720)){
                $this->music720->saveAs($address . $fileName);
                return true;
            }elseif (isset($this->music1080)){
                $this->music1080->saveAs($address . $fileName);
                return true;
            }elseif (isset($this->musicFile)){
                $this->musicFile->saveAs($address . $fileName);
                return true;
            }

            return true;
        } else {
            return false;
        }
    }
}