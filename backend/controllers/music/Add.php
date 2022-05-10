<?php

namespace backend\controllers\music;

use backend\models\artist\UploadForm;
use backend\models\music\AddMusicForm;
use Yii;
use yii\base\Action;
use common\models\music\Music;
use common\models\artist\Artist;
use yii\imagine\Image;
use yii\web\UploadedFile;
use getID3;

class Add extends Action {

    public function run() {

        $model = new AddMusicForm();
        $model->special = Music::STATUS_DISABLED;
        $model->music_no = 0;

        $artist = Artist::find()
            ->select(['id as value', 'name as  label','id as id'])
            ->asArray()
            ->all();

        $music = Music::find()
            ->select(['id as value', 'key as  label','id as id'])
            ->asArray()
            ->all();

        if ($model->load(Yii::$app->request->post()) && $m = $model->add()) {



            /**
             * upload image
             */
            $modelFile = new UploadForm();
            $modelFile->imageFile = UploadedFile::getInstance($model, 'image');

            $directory = \Yii::$app->params['uploadUrl'].$m->directory;

            if($modelFile->imageFile != null){

                $modelFile->upload($directory.'/cover/cover-', $m->image);

                $fileName = $directory . '/cover/cover-' . $m->image;
                Image::thumbnail($fileName, 212, 212)->save($directory.'/cover/'.$m->image , ['quality' => 100]);
                Image::thumbnail($fileName, 50, 50)->save($directory.'/cover/50'.$m->image , ['quality' => 100]);
                Image::thumbnail($fileName, 300, 300)->save($directory.'/cover/300'.$m->image , ['quality' => 100]);
                Image::thumbnail($fileName, 480, 480)->save($directory.'/cover/480'.$m->image , ['quality' => 100]);

            }

            /**
             * upload mp3
             */

            $modelFile = new UploadForm();


            $albumAddress = '';
            $album = Music::find()->where(['id' => $m->music_id])->one();

            if ($m->music_id != '' && $album->type == Music::TYPE_ALBUM){
                $albumAddress = $album->key_pure.'/';
                $model->createDirectory(\Yii::$app->params['uploadUrl'].$album->directory . '/mp3/'.$album->key_pure.'/', 0775, true);
            }

            $modelFile->musicFile = UploadedFile::getInstance($model, 'music128Upload');
            if($modelFile->musicFile != null){

                $modelFile->upload($directory.'/mp3/'.$albumAddress, $m->dl_link." [128].mp3");
                $this->tag_music($m,$directory.'/mp3/'.$albumAddress.$m->dl_link." [128].mp3");
            }elseif($model->music128Address){
                copy($model->music128Address, $directory.'/mp3/'.$albumAddress.$m->dl_link." [128].mp3");
                $this->tag_music($m,$directory.'/mp3/'.$albumAddress.$m->dl_link." [128].mp3");
            }

            $modelFile->musicFile = UploadedFile::getInstance($model, 'music320Upload');
            if($modelFile->musicFile != null){

                $modelFile->upload($directory.'/mp3/'.$albumAddress, $m->dl_link." [320].mp3");
                $this->tag_music($m,$directory.'/mp3/'.$albumAddress.$m->dl_link." [320].mp3");
            }elseif($model->music320Address){
                copy($model->music320Address, $directory.'/mp3/'.$albumAddress.$m->dl_link." [320].mp3");
                $this->tag_music($m,$directory.'/mp3/'.$albumAddress.$m->dl_link." [320].mp3");
            }


            /**
             * upload video
             */

            $modelFile->musicFile = UploadedFile::getInstance($model, 'music480Upload');
            if($modelFile->musicFile != null){
                $modelFile->upload($directory.'/video/', $m->dl_link." 480p [iNavar.com].mp4");
            }elseif($model->music480Address){
                copy($model->music480Address, $directory.'/video/'.$m->dl_link." 480p [iNavar.com].mp4");
            }

            $modelFile->musicFile = UploadedFile::getInstance($model, 'music720Upload');
            if($modelFile->musicFile != null){
                $modelFile->upload($directory.'/video/', $m->dl_link." 720p [iNavar.com].mp4");
            }elseif($model->music720Address){
                copy($model->music720Address, $directory.'/video/'.$m->dl_link." 720p [iNavar.com].mp4");
            }

            $modelFile->musicFile = UploadedFile::getInstance($model, 'music1080Upload');
            if($modelFile->musicFile != null){
                $modelFile->upload($directory.'/video/', $m->dl_link." 1080p [iNavar.com].mp4");
            }elseif($model->music1080Address){
                copy($model->music1080Address, $directory.'/video/'.$m->dl_link." 1080p [iNavar.com].mp4");
            }


            if ($model->type == Music::TYPE_ALBUM){

                $ftp = Yii::$app->helper->ftpLogin();
                $ftp->mkdir($m->directory.'/mp3/'.$m->key_pure);
            }

            //$directory2 = "/var/www/musicplus.info/public_html/backend/web/upload/".$m->directory;

            //ftp
            if ($m->music_id != '' && $album->type == Music::TYPE_ALBUM){

                $ftp = Yii::$app->helper->ftpLogin();
                $ftp->putAll($directory.'/cover/', $m->directory.'/cover/', FTP_BINARY);
                $ftp->putAll($directory.'/mp3/'.$album->key_pure, $m->directory.'/mp3/'.$album->key_pure, FTP_BINARY);

//                $this->recurse_copy($directory2.'/mp3/'.$album->key_pure, '/var/www/navar_en/public_html/web/storage/media/'.$m->directory.'/mp3/'.$album->key_pure);
//                $this->recurse_copy($directory2.'/cover/', '/var/www/navar_en/public_html/web/storage/media/'.$m->directory.'/cover/');

                Yii::$app->helper->deleteDirectoryFilesAndDirectory($directory.'/mp3/'.$album->key_pure);
                Yii::$app->helper->deleteDirectoryFiles($directory);
            }else{
                $ftp = Yii::$app->helper->ftpLogin();
                $ftp->putAll($directory.'/cover/', $m->directory.'/cover/', FTP_BINARY);
                $ftp->putAll($directory.'/mp3/', $m->directory.'/mp3/', FTP_BINARY);
                $ftp->putAll($directory.'/video/', $m->directory.'/video/', FTP_BINARY);

//                $this->recurse_copy($directory2.'/mp3/', '/var/www/navar_en/public_html/web/storage/media/'.$m->directory.'/mp3/');
//                $this->recurse_copy($directory2.'/cover/', '/var/www/navar_en/public_html/web/storage/media/'.$m->directory.'/cover/');
//                $this->recurse_copy($directory2.'/video/', '/var/www/navar_en/public_html/web/storage/media/'.$m->directory.'/video/');

                Yii::$app->helper->deleteDirectoryFiles($directory);
            }

            if ($model->type == Music::TYPE_ALBUM){
                Yii::$app->helper->deleteDirectoryFilesAndDirectory($directory.'/mp3/'.$m->key_pure);
            }

            pclose(popen('cd '.\Yii::getAlias('@app').' && cd .. && php yii album/song64 '.$m->id, 'r'));
            pclose(popen('cd '.\Yii::getAlias('@app').' && cd .. && php yii music/quality '.$m->id, 'r'));

            Yii::$app->session->setFlash('success', Yii::t('app', 'Music successfully created.'));

            Yii::$app->response->redirect(['music/update', 'id' => $m->id]);
        }
        
        return Yii::$app->request->isAjax ?
            $this->controller->renderAjax('add', [
                'model' => $model,
                'artist' => $artist,
                'music' => $music,
            ]) :
            $this->controller->render('add', [
                'model' => $model,
                'artist' => $artist,
                'music' => $music,
        ]);
    }

    function recurse_copy($src, $dst)
    {
        $dir = opendir($src);
        @mkdir($dst);
        while(false !== ( $file = readdir($dir)) ) {
            if (( $file != '.' ) && ( $file != '..' )) {
                if ( is_dir($src . '/' . $file) ) {
                    $this->recurse_copy($src . '/' . $file,$dst . '/' . $file);
                }
                else {
                    copy($src . '/' . $file,$dst . '/' . $file);
                }
            }
        }
        closedir($dir);
    }


    public function tag_music($model,$address)
    {


        $TextEncoding = 'UTF-8';
        //require_once('./tag/getid3.php');
        // Initialize getID3 engine
        $getID3 = new getID3();
        $getID3->setOption(array('encoding'=>$TextEncoding));

        //require_once('./tag/write.php');
        // Initialize getID3 tag-writing module
        $tagwriter = new \getid3_writetags();
        //$tagwriter->filename = '/path/to/file.mp3';
        $tagwriter->filename = $address;

        $tagwriter->tagformats = array('id3v1', 'id3v2.3');
        //$tagwriter->tagformats = array('id3v2.3');

        // set various options (optional)
        $tagwriter->overwrite_tags    = true;  // if true will erase existing tag data and write only passed data; if false will merge passed data with existing tag data (experimental)
        $tagwriter->remove_other_tags = false; // if true removes other tag formats (e.g. ID3v1, ID3v2, APE, Lyrics3, etc) that may be present in the file and only write the specified tag format(s). If false leaves any unspecified tag formats as-is.
        $tagwriter->tag_encoding      = $TextEncoding;
        $tagwriter->remove_other_tags = true;

        // populate data array
        $date = date("Y", $model->created_at);

        if($model->music_id == null || $model->music_no == 00 || $model->music_no == 0){
            $musicNo = '';
        }else{
            $musicNo = $model->music_no. ' ';
        }

        if ($model->music_id == ''){
            $albumName = 'Single Track';
        }elseif($album = Music::find()->where(['id' => $model->music_id, 'type' => Music::TYPE_ALBUM])->one()){
            $albumName = $album->name;
        }

        if ($model->genre == Music::GENRE_PERSIAN){
            $genre = 'Persian';
        }else{
            $genre = 'Foreign';
        }

        if ($model->artist_name == ''){
            $artistName = Artist::find()->where(['id' => $model->artist_id])->one()->name;
        }else{
            $artistName = $model->artist_name;
        }

        $TagData = array(
            'title'         => [$musicNo.ucwords($model->name)],
            'artist'        => [ucwords($artistName)." [Navar.xyz]"],
            'album'         => [$albumName],
            'year'          => array(isset($date) ? $date : "0000"),
            'genre'         => [$genre],
            'comment'       => array('Navar.xyz'),
            'track'         => array(isset($model->music_no) ? $model->music_no : ''),
            'publisher'     => ['Navar.xyz'],
        );
        $path = Yii::$app->params['uploadUrl'];
        if(file_exists($path.$model->directory."/cover/".'cover-'.$model->image))
        {
            $albumPath = $path.$model->directory."/cover/".'cover-'.$model->image;
        }else
        {
            $albumPath = $path.$model->directory."/cover/".$model->image;
        }

        //var_dump($albumPath);die;

        $fd = fopen($albumPath, 'rb');
        $APICdata = fread($fd, filesize($albumPath));
        fclose($fd);

        //var_dump($APICdata);die;

        if($APICdata) {
            $TagData += array(
                'attached_picture' => array(0 => array(
                    'data'          => $APICdata,
                    'picturetypeid' => 0x03,
                    'description'   => ucwords($model->name).' - '.ucwords($model->name),
                    'mime'          => 'image/jpeg'
                ))
            );
        }








        $tagwriter->tag_data = $TagData;

        // write tags
        if ($tagwriter->WriteTags()) {
            echo 'Successfully wrote tags<br>';
            if (!empty($tagwriter->warnings)) {
                echo 'There were some warnings:<br>'.implode('<br><br>', $tagwriter->warnings);
            }
        } else {
            echo 'Failed to write tags!<br>'.implode('<br><br>', $tagwriter->errors);
        }



        if (!empty($albumPath)) {
            if (in_array('id3v2.4', $tagwriter->tagformats) || in_array('id3v2.3', $tagwriter->tagformats) || in_array('id3v2.2', $tagwriter->tagformats)) {
                if (is_uploaded_file($albumPath)) {
                    ob_start();
                    if ($fd = fopen($albumPath, 'rb')) {
                        ob_end_clean();
                        $APICdata = fread($fd, filesize($albumPath));
                        fclose ($fd);

                        list($APIC_width, $APIC_height, $APIC_imageTypeID) = GetImageSize($albumPath);
                        $imagetypes = array(1=>'gif', 2=>'jpeg', 3=>'png');
                        if (isset($imagetypes[$APIC_imageTypeID])) {

                            $TagData['attached_picture'][0]['data']          = $APICdata;
                            $TagData['attached_picture'][0]['picturetypeid'] = '0x03';
                            $TagData['attached_picture'][0]['description']   = ucwords($model->name).' - '.ucwords($model->name);
                            $TagData['attached_picture'][0]['mime']          = 'image/'.$imagetypes[$APIC_imageTypeID];

                        } else {
                            echo '<b>invalid image format (only GIF, JPEG, PNG)</b><br>';
                        }
                    } else {
                        $errormessage = ob_get_contents();
                        ob_end_clean();
                        echo '<b>cannot open '.$albumPath.'</b><br>';
                    }
                } else {
                    echo '<b>!is_uploaded_file('.$albumPath.')</b><br>';
                }
            } else {
                echo '<b>WARNING:</b> Can only embed images for ID3v2<br>';
            }
        }


        /*******************************************
        if (in_array('id3v2.4', $tagwriter->tagformats) || in_array('id3v2.3', $tagwriter->tagformats) || in_array('id3v2.2', $tagwriter->tagformats)) {

        ob_start();
        if ($fd = fopen($albumPath, 'rb')) {
        ob_end_clean();
        $APICdata = fread($fd, filesize($albumPath));
        fclose ($fd);

        list($APIC_width, $APIC_height, $APIC_imageTypeID) = GetImageSize($albumPath);
        $imagetypes = array(1=>'gif', 2=>'jpeg', 3=>'png');
        if (isset($imagetypes[$APIC_imageTypeID])) {

        $TagData['attached_picture'][0]['data']          = $APICdata;
        //$TagData['attached_picture'][0]['picturetypeid'] = $_POST['APICpictureType'];
        $TagData['attached_picture'][0]['description']   = $albumPath;
        $TagData['attached_picture'][0]['mime']          = $albumPath;

        } else {
        echo '<b>invalid image format (only GIF, JPEG, PNG)</b><br>';
        }
        } else {
        $errormessage = ob_get_contents();
        ob_end_clean();
        echo '<b>cannot open '."c:/folder.jpg".'</b><br>';
        }

        } else {
        echo '<b>WARNING:</b> Can only embed images for ID3v2<br>';
        }
         */

    }

}
