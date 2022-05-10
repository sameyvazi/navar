<?php

namespace backend\controllers\music;

use backend\models\artist\UpdateForm;
use backend\models\artist\UploadForm;
use backend\models\music\AddMusicForm;
use backend\models\music\UpdateMusicForm;
use common\models\artist\Artist;
use common\models\music\Music;
use common\models\music\MusicArtist;
use common\models\tag\TagRelation;
use Yii;
use yii\base\Action;
use yii\imagine\Image;
use yii\web\UploadedFile;

class Update extends Action {

    public function run($id) {

        $model = $this->controller->findModel($id);

        $artists = $this->controller->findModelArtists($id);

        if ($model->music_no < 10){
            $model->music_no = '0'.$model->music_no;
        }

        $updateModel = new UpdateMusicForm();

        $tagRelations = TagRelation::find()->where(['post_id' => $id])->with('tags')->all();
        foreach ($tagRelations as $tag){
            $updateModel->tag .= $tag->tags->name. "\n";
        }
        $updateModel->tag = rtrim($updateModel->tag);

        foreach ($artists as $artist){
            if ($artist->activity == Artist::TYPE_SINGER){
                $updateModel->singer_id[] = $artist->artist_id;
            }elseif ($artist->activity == Artist::TYPE_COMPOSER){
                $updateModel->composer_id[] = $artist->artist_id;
            }elseif ($artist->activity == Artist::TYPE_LYRIC){
                $updateModel->lyric_id[] = $artist->artist_id;
            }elseif ($artist->activity == Artist::TYPE_REGULATOR){
                $updateModel->regulator_id[] = $artist->artist_id;
            }elseif ($artist->activity == Artist::TYPE_MUSICIANS){
                $updateModel->musician_id[] = $artist->artist_id;
            }elseif ($artist->activity == Artist::TYPE_MONTAGE){
                $updateModel->montage_id[] = $artist->artist_id;
            }elseif ($artist->activity == Artist::TYPE_DIRECTOR){
                $updateModel->director_id[] = $artist->artist_id;
            }

        }


        $artist = Artist::find()
            ->select(['id as value', 'name as  label','id as id'])
            ->asArray()
            ->all();

        $music = Music::find()
            ->select(['id as value', 'key_pure as  label','id as id'])
            ->asArray()
            ->all();


        date_default_timezone_set("Asia/Tehran");
        $model->created_at = date('Y-m-d H:i:s', $model->created_at);
        
        if ($updateModel->load(Yii::$app->request->post())) {

            $updateModel->save($model);

            /*
             * upload image
             */

            $modelFile = new UploadForm();
            $modelFile->imageFile = UploadedFile::getInstance($updateModel, 'image');

            $directory = \Yii::$app->params['uploadUrl'].$model->directory;
            
            if($modelFile->imageFile != null) {

                $modelFile->upload($directory . '/cover/cover-', $model->image);
                $fileName = $directory . '/cover/cover-' . $model->image;
                Image::thumbnail($fileName, 212, 212)->save($directory.'/cover/'.$model->image , ['quality' => 100]);
                Image::thumbnail($fileName, 50, 50)->save($directory.'/cover/50'.$model->image , ['quality' => 100]);
                Image::thumbnail($fileName, 300, 300)->save($directory.'/cover/300'.$model->image , ['quality' => 100]);
                Image::thumbnail($fileName, 480, 480)->save($directory.'/cover/480'.$model->image , ['quality' => 100]);
            }


            /**
             * upload mp3
             */

            $modelFile = new UploadForm();

            $albumAddress = '';
            $album = Music::find()->where(['id' => $model->music_id])->one();

            if ($model->music_id != '' && $album->type == Music::TYPE_ALBUM){
                $albumAddress = $album->key_pure.'/';
                $updateModel->createDirectory(\Yii::$app->params['uploadUrl'].$album->directory . '/mp3/'.$album->key_pure.'/', 0775, true);
            }

            $uploadFile = false;
            $modelFile->musicFile = UploadedFile::getInstance($updateModel, 'music128Upload');
            if($modelFile->musicFile != null){

                $modelFile->upload($directory.'/mp3/'.$albumAddress, $model->dl_link." [128].mp3");
                $this->tag_music($model,$directory.'/mp3/'.$albumAddress.$model->dl_link." [128].mp3");
                $uploadFile = true;
            }elseif($updateModel->music128Address){

                copy($updateModel->music128Address, $directory.'/mp3/'.$albumAddress.$model->dl_link." [128].mp3");
                $this->tag_music($model,$directory.'/mp3/'.$albumAddress.$model->dl_link." [128].mp3");
                $uploadFile = true;
            }

            $modelFile->musicFile = UploadedFile::getInstance($updateModel, 'music320Upload');
            if($modelFile->musicFile != null){

                $modelFile->upload($directory.'/mp3/'.$albumAddress, $model->dl_link." [320].mp3");
                $this->tag_music($model,$directory.'/mp3/'.$albumAddress.$model->dl_link." [320].mp3");
                $uploadFile = true;
            }elseif($updateModel->music320Address){
                copy($updateModel->music320Address, $directory.'/mp3/'.$albumAddress.$model->dl_link." [320].mp3");
                $this->tag_music($model,$directory.'/mp3/'.$albumAddress.$model->dl_link." [320].mp3");
                $uploadFile = true;
            }


            /**
             * upload video
             */
            ini_set('max_execution_time', 300);

            $modelFile->musicFile = UploadedFile::getInstance($updateModel, 'music480Upload');
            if($modelFile->musicFile != null){
                $modelFile->upload($directory.'/video/', $model->dl_link." 480p [iNavar.com].mp4");
            }elseif($updateModel->music480Address){
                copy($updateModel->music480Address, $directory.'/video/'.$model->dl_link." 480p [iNavar.com].mp4");
            }

            $modelFile->musicFile = UploadedFile::getInstance($updateModel, 'music720Upload');
            if($modelFile->musicFile != null){
                $modelFile->upload($directory.'/video/', $model->dl_link." 720p [iNavar.com].mp4");
            }elseif($updateModel->music720Address){
                copy($updateModel->music720Address, $directory.'/video/'.$model->dl_link." 720p [iNavar.com].mp4");
            }

            $modelFile->musicFile = UploadedFile::getInstance($updateModel, 'music1080Upload');
            if($modelFile->musicFile != null){
                $modelFile->upload($directory.'/video/', $model->dl_link." 1080p [iNavar.com].mp4");
            }elseif($updateModel->music1080Address){
                copy($updateModel->music1080Address, $directory.'/video/'.$model->dl_link." 1080p [iNavar.com].mp4");
            }


            //ftp
            //$directory2 = "/var/www/musicplus.info/public_html/backend/web/upload/".$model->directory;
            if ($model->music_id != '' && $album->type == Music::TYPE_ALBUM){

                $ftp = Yii::$app->helper->ftpLogin();
                $ftp->putAll($directory.'/cover/', $model->directory.'/cover/', FTP_BINARY);
                $ftp->putAll($directory.'/mp3/'.$album->key_pure, $model->directory.'/mp3/'.$album->key_pure, FTP_BINARY);

//                $this->recurse_copy($directory2.'/mp3/'.$album->key_pure, '/var/www/navar_en/public_html/web/storage/media/'.$model->directory.'/mp3/'.$album->key_pure);
//                $this->recurse_copy($directory2.'/cover/', '/var/www/navar_en/public_html/web/storage/media/'.$model->directory.'/cover/');

                Yii::$app->helper->deleteDirectoryFilesAndDirectory($directory.'/mp3/'.$album->key_pure);
                Yii::$app->helper->deleteDirectoryFiles($directory);
            }else{
                $ftp = Yii::$app->helper->ftpLogin();
                $ftp->putAll($directory.'/cover/', $model->directory.'/cover/', FTP_BINARY);
                $ftp->putAll($directory.'/mp3/', $model->directory.'/mp3/', FTP_BINARY);
                $ftp->putAll($directory.'/video/', $model->directory.'/video/', FTP_BINARY);

//                $this->recurse_copy($directory2.'/mp3/', '/var/www/navar_en/public_html/web/storage/media/'.$model->directory.'/mp3/');
//                $this->recurse_copy($directory2.'/cover/', '/var/www/navar_en/public_html/web/storage/media/'.$model->directory.'/cover/');
//                $this->recurse_copy($directory2.'/video/', '/var/www/navar_en/public_html/web/storage/media/'.$model->directory.'/video/');

                Yii::$app->helper->deleteDirectoryFiles($directory);
            }

            if ($model->type == Music::TYPE_ALBUM){
                //Yii::$app->helper->deleteDirectoryFilesAndDirectory($directory.'/mp3/'.$model->key_pure);
            }

            if ($uploadFile){
                pclose(popen('cd '.\Yii::getAlias('@app').' && cd .. && php yii album/song64 '.$id, 'r'));
                pclose(popen('cd '.\Yii::getAlias('@app').' && cd .. && php yii music/quality '.$id, 'r'));
            }
            
            Yii::$app->session->setFlash('success', Yii::t('app', 'Music successfully updated.'));
        }
        else
        {

            $updateModel->setAttributes($model->getAttributes());
        }

        return Yii::$app->request->isAjax ?
            $this->controller->renderAjax('manage', [
                'model' => $updateModel,
                'artist' => $artist,
                'music' => $music,
            ]) :
            $this->controller->render('manage', [
                'model' => $updateModel,
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
        $getID3 = new \getID3();
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
