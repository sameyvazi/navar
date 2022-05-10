<?php

namespace console\controllers;


use common\models\artist\Artist as Artists;
use common\models\music\Music;
use common\models\music\MusicArtist;
use yii\console\Controller;
use Yii;
use yii\helpers\FileHelper;

class AlbumController extends Controller {
    


    public function actionZip($id){


        set_time_limit(0);

        $albums = Music::find()
            ->where(['type' => Music::TYPE_ALBUM, 'id' => (int)$id])
            //->where(['type' => Music::TYPE_ALBUM])
            //->andWhere(['>', 'id', 6700])
            ->all();


        foreach ($albums as $album){

            $musics = Music::find()->where(['music_id' => $album->id, 'type' => Music::TYPE_MP3])->all();

            $create128 = false;

            foreach ($musics as $music){



                //dl 320

                $file320 [] = 'static/albums/'.$music->dl_link.' [320].mp3';
                $url = "http://static.musicplus.info/storage/media/"."$music->directory"."/mp3/".strtolower($album->key)."/".rawurlencode($music->dl_link.' [320].mp3');

                if(@get_headers($url)[0] == "HTTP/1.0 200 OK" || @get_headers($url)[0] == "HTTP/1.1 200 OK"){

                    $fp = fopen ('static/albums/'.$music->dl_link.' [320].mp3', 'w+');
                    $ch = curl_init(str_replace(" ","%20",$url));
                    curl_setopt($ch, CURLOPT_TIMEOUT, 50);
                    curl_setopt($ch, CURLOPT_FILE, $fp);
                    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                    $data = curl_exec($ch);
                    curl_close($ch);
                }



                //dl 128
                $file128 [] = 'static/albums/'.$music->dl_link.' [128].mp3';
                $url = "http://static.musicplus.info/storage/media/"."$music->directory"."/mp3/".strtolower($album->key)."/".rawurlencode($music->dl_link.' [128].mp3');

                if(@get_headers($url)[0] == "HTTP/1.0 200 OK" || @get_headers($url)[0] == "HTTP/1.1 200 OK"){

                    $fp = fopen ('static/albums/'.$music->dl_link.' [128].mp3', 'w+');
                    $ch = curl_init(str_replace(" ","%20",$url));
                    curl_setopt($ch, CURLOPT_TIMEOUT, 50);
                    curl_setopt($ch, CURLOPT_FILE, $fp);
                    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                    $data = curl_exec($ch);
                    curl_close($ch);

                    $music->dl_link = str_replace("(","\(",$music->dl_link);
                    $music->dl_link = str_replace(")","\)",$music->dl_link);
                    $music->dl_link = str_replace("&","\&",$music->dl_link);
                    $music->dl_link = str_replace(",","\,",$music->dl_link);

                    $fileName = str_replace(" ","\ ",$music->dl_link).'\ [128].mp3';
                    $fileNameNew96 = str_replace(" ","\ ",$music->dl_link).'\ [96].mp3';
                    exec("ffmpeg -i static/albums/$fileName -ab 64000 static/albums/$fileNameNew96");

                }elseif(file_exists('static/albums/'.$music->dl_link.' [320].mp3')){


                    $music->dl_link = str_replace("(","\(",$music->dl_link);
                    $music->dl_link = str_replace(")","\)",$music->dl_link);
                    $music->dl_link = str_replace("&","\&",$music->dl_link);
                    $music->dl_link = str_replace(",","\,",$music->dl_link);
                    //convert 320 to 128
                    $fileName = str_replace(" ","\ ",$music->dl_link).'\ [320].mp3';
                    $fileNameNew = str_replace(" ","\ ",$music->dl_link).'\ [128].mp3';
                    $fileNameNew96 = str_replace(" ","\ ",$music->dl_link).'\ [96].mp3';

                    exec("ffmpeg -i static/albums/$fileName -ab 128000 static/albums/$fileNameNew");
                    exec("ffmpeg -i static/albums/$fileName -ab 64000 static/albums/$fileNameNew96");

                    $create128 = true;
                }

                //vars
                $valid_files320 = array();
                $valid_files128 = array();

                if (is_array($file320)) {
                    foreach ($file320 as $file) {
                        if (file_exists($file)) {
                            $valid_files320[] = $file;
                        }
                    }
                }

                if (is_array($file128)) {
                    foreach ($file128 as $file) {
                        if (file_exists($file)) {
                            $valid_files128[] = $file;
                        }
                    }
                }


            }




            //if we have good files...
            if (count($valid_files320)) {
                //create the archive
                $zip = new \ZipArchive();

                $destination320 = 'static/albums/'.$album->dl_link. ' [320].zip';
                if ($zip->open($destination320, file_exists($destination320) ? \ZipArchive::OVERWRITE : \ZipArchive::CREATE) !== true) {
                    return false;
                }
                //add the files
                foreach ($valid_files320 as $file) {
                    $fileNew = str_replace('static/albums/', '', $file);
                    $zip->addFile($file, $album->dl_link.'/'.$fileNew);
                }

                //close the zip -- done!
                $zip->close();

            }




            //if we have good files...
            if (count($valid_files128)) {
                //create the archive
                $zip = new \ZipArchive();

                $destination128 = 'static/albums/'.$album->dl_link. ' [128].zip';
                if ($zip->open($destination128, file_exists($destination128) ? \ZipArchive::OVERWRITE : \ZipArchive::CREATE) !== true) {
                    return false;
                }
                //add the files
                foreach ($valid_files128 as $file) {
                    $fileNew = str_replace('static/albums/', '', $file);
                    $zip->addFile($file, $album->dl_link.'/'.$fileNew);
                }

                //close the zip -- done!
                $zip->close();

            }


            //die;

            //delete files

            if (count($valid_files320)) {

                foreach ($valid_files320 as $file) {
                    unlink($file); // delete file
                }
            }


            if (count($valid_files128) && $create128 == false) {

                foreach ($valid_files128 as $file) {
                    unlink($file); // delete file
                }
            }

            //die();

            $ftp = Yii::$app->helper->ftpLogin();

            //$ftp->putAll('static/albums/', $album->directory.'/cover/', FTP_BINARY);
            $ftp->putAll('static/albums/', $music->directory."/mp3/".strtolower($album->key), FTP_BINARY);

            $albumKey = strtolower($album->key);

            //exec("cp -R static/albums/* /home/navar/public_html/navar_en/web/storage/media/$music->directory/mp3/$albumKey");

            Yii::$app->helper->deleteDirectoryFiles('static/albums/');




            die;



        }

    }

    public function actionSong($id){


        set_time_limit(0);

//        $musics = Music::find()
//            ->where(['>', 'id', 3407])
//            ->andWhere(['<', 'id', 6700])
//            ->andWhere(['type' => Music::TYPE_MP3])
//            ->all();

//        $musics = Music::find()
//            ->where(['>', 'id', 9517])
//            //->andWhere(['<=', 'id', 9517])
//            ->andWhere(['music_id' => null])
//            ->andWhere(['type' => Music::TYPE_MP3])
//            ->all();

        $musics = Music::find()
            ->where(['id' => $id])
            ->andWhere(['type' => Music::TYPE_MP3])
            ->all();


        $create128 = false;

        foreach ($musics as $music){

            $albumKey = '';
            if ($music->music_id != null && $musicAlbum = Music::find()->where(['id' => $music->music_id])->one()){

                if($musicAlbum->type == Music::TYPE_ALBUM && $musicAlbum->id > 6700){
                    $albumKey = $musicAlbum->key_pure.'/';
                }
            }

            $url128 = "http://static.musicplus.info/storage/media/"."$music->directory"."/mp3/".rawurlencode($music->dl_link.' [128].mp3');
            $url320 = "http://static.musicplus.info/storage/media/"."$music->directory"."/mp3/".rawurlencode($music->dl_link.' [320].mp3');


            if(@get_headers($url128)[0] == "HTTP/1.0 200 OK" || @get_headers($url128)[0] == "HTTP/1.1 200 OK"){


                $file128 [] = 'static/albums/'.$music->dl_link.' [128].mp3';

                $fp = fopen ('static/albums/'.$music->dl_link.' [128].mp3', 'w+');
                $ch = curl_init(str_replace(" ","%20",$url128));
                curl_setopt($ch, CURLOPT_TIMEOUT, 50);
                curl_setopt($ch, CURLOPT_FILE, $fp);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                $data = curl_exec($ch);
                curl_close($ch);


                $music->dl_link = str_replace("(","\(",$music->dl_link);
                $music->dl_link = str_replace(")","\)",$music->dl_link);
                $music->dl_link = str_replace("&","\&",$music->dl_link);
                $music->dl_link = str_replace(",","\,",$music->dl_link);

                $fileName = str_replace(" ","\ ",$music->dl_link).'\ [128].mp3';
                $fileNameNew96 = str_replace(" ","\ ",$music->dl_link).'\ [96].mp3';
                exec("ffmpeg -i static/albums/$fileName -ab 64000 static/albums/$fileNameNew96");

            }else{

                $file320 [] = 'static/albums/'.$music->dl_link.' [320].mp3';

                $fp = fopen ('static/albums/'.$music->dl_link.' [320].mp3', 'w+');
                $ch = curl_init(str_replace(" ","%20",$url320));
                curl_setopt($ch, CURLOPT_TIMEOUT, 50);
                curl_setopt($ch, CURLOPT_FILE, $fp);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                $data = curl_exec($ch);
                curl_close($ch);

                $music->dl_link = str_replace("(","\(",$music->dl_link);
                $music->dl_link = str_replace(")","\)",$music->dl_link);
                $music->dl_link = str_replace("&","\&",$music->dl_link);
                $music->dl_link = str_replace(",","\,",$music->dl_link);

                //convert 320 to 128
                $fileName = str_replace(" ","\ ",$music->dl_link).'\ [320].mp3';
                $fileNameNew = str_replace(" ","\ ",$music->dl_link).'\ [128].mp3';
                $fileNameNew96 = str_replace(" ","\ ",$music->dl_link).'\ [96].mp3';

                exec("ffmpeg -i static/albums/$fileName -ab 128000 static/albums/$fileNameNew");
                exec("ffmpeg -i static/albums/$fileName -ab 64000 static/albums/$fileNameNew96");

                $create128 = true;
            }

            if (isset($file320) && count($file320)) {
                foreach ($file320 as $file) {
                    unlink($file); // delete file
                }
            }

            if (isset($file128) && count($file128) && $create128 == false) {

                foreach ($file128 as $file) {
                    unlink($file); // delete file
                }
            }


            $ftp = Yii::$app->helper->ftpLogin();

            //$ftp->putAll('static/albums/', $album->directory.'/cover/', FTP_BINARY);
            $ftp->putAll('static/albums/', $music->directory."/mp3/$albumKey", FTP_BINARY);
            //exec("cp -R static/albums/* static/storage/media/$music->directory/mp3/$albumKey");
            Yii::$app->helper->deleteDirectoryFiles('static/albums/');

            echo '---------- '.$music->id.' ----------'."\n";

            unset($file128);
            unset($file320);

        }
    }

    public function actionSong64($id){


        set_time_limit(0);

//        $musics = Music::find()
//            ->where(['>=', 'id', $id])
//            ->andWhere(['type' => Music::TYPE_MP3])
//            ->andWhere(['=', 'lq', 2])
//            ->all();

//        $musics = Music::find()
//            ->where(['>', 'id', 9517])
//            //->andWhere(['<=', 'id', 9517])
//            ->andWhere(['music_id' => null])
//            ->andWhere(['type' => Music::TYPE_MP3])
//            ->all();

        $musics = Music::find()
            ->where(['id' => $id])
            ->andWhere(['type' => Music::TYPE_MP3])
            ->all();


        $create128 = false;

        foreach ($musics as $music){

            $albumKey = '';
            if ($music->music_id != null && $musicAlbum = Music::find()->where(['id' => $music->music_id])->one()){

                if($musicAlbum->type == Music::TYPE_ALBUM && $musicAlbum->id > 6700){
                    $albumKey = $musicAlbum->key_pure.'/';
                }
            }


            $url128 = "http://static.musicplus.info/storage/media/"."$music->directory"."/mp3/".$albumKey.rawurlencode($music->dl_link.' [128].mp3');
            $url320 = "http://static.musicplus.info/storage/media/"."$music->directory"."/mp3/".$albumKey.rawurlencode($music->dl_link.' [320].mp3');

            //var_dump($url128);die;
            //var_dump(@get_headers($url128)[0] == "HTTP/1.0 200 OK");die;

            if(@get_headers($url128)[0] == "HTTP/1.0 200 OK" || @get_headers($url128)[0] == "HTTP/1.1 200 OK"){


                $file128 [] = 'static/albums/'.$music->dl_link.' [128].mp3';

                $fp = fopen ('static/albums/'.$music->dl_link.' [128].mp3', 'w+');
                $ch = curl_init(str_replace(" ","%20",$url128));
                curl_setopt($ch, CURLOPT_TIMEOUT, 50);
                curl_setopt($ch, CURLOPT_FILE, $fp);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                $data = curl_exec($ch);
                curl_close($ch);


                $music->dl_link = str_replace("(","\(",$music->dl_link);
                $music->dl_link = str_replace(")","\)",$music->dl_link);
                $music->dl_link = str_replace("&","\&",$music->dl_link);
                $music->dl_link = str_replace(",","\,",$music->dl_link);

                $fileName = str_replace(" ","\ ",$music->dl_link).'\ [128].mp3';
                $fileNameNew96 = str_replace(" ","\ ",$music->dl_link).'\ [96].mp3';
                exec("ffmpeg -i static/albums/$fileName -ab 64000 static/albums/$fileNameNew96");

                if (@get_headers($url320)[0] != "HTTP/1.0 200 OK" || @get_headers($url320)[0] == "HTTP/1.1 200 OK"){
                    $fileNameNew320 = str_replace(" ","\ ",$music->dl_link).'\ [320].mp3';
                    exec("cp static/albums/$fileName static/albums/$fileNameNew320");
                }
            }else{

                $file320 [] = 'static/albums/'.$music->dl_link.' [320].mp3';

                $fp = fopen ('static/albums/'.$music->dl_link.' [320].mp3', 'w+');
                $ch = curl_init(str_replace(" ","%20",$url320));
                curl_setopt($ch, CURLOPT_TIMEOUT, 50);
                curl_setopt($ch, CURLOPT_FILE, $fp);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                $data = curl_exec($ch);
                curl_close($ch);

                $music->dl_link = str_replace("(","\(",$music->dl_link);
                $music->dl_link = str_replace(")","\)",$music->dl_link);
                $music->dl_link = str_replace("&","\&",$music->dl_link);
                $music->dl_link = str_replace(",","\,",$music->dl_link);

                //convert 320 to 128
                $fileName = str_replace(" ","\ ",$music->dl_link).'\ [320].mp3';
                $fileNameNew = str_replace(" ","\ ",$music->dl_link).'\ [128].mp3';
                $fileNameNew96 = str_replace(" ","\ ",$music->dl_link).'\ [96].mp3';

                exec("ffmpeg -i static/albums/$fileName -ab 128000 static/albums/$fileNameNew");
                exec("ffmpeg -i static/albums/$fileName -ab 64000 static/albums/$fileNameNew96");

                $create128 = true;
            }

            if (isset($file320) && count($file320)) {
                foreach ($file320 as $file) {
                    unlink($file); // delete file
                }
            }

            if (isset($file128) && count($file128) && $create128 == false) {

                foreach ($file128 as $file) {
                    unlink($file); // delete file
                }
            }


            $ftp = Yii::$app->helper->ftpLogin();
//
//            //$ftp->putAll('static/albums/', $album->directory.'/cover/', FTP_BINARY);
            $ftp->putAll('static/albums/', $music->directory."/mp3/$albumKey", FTP_BINARY);
            //exec("cp -R static/albums/* /home/navar/public_html/navar_en/web/storage/media/$music->directory/mp3/$albumKey");
//            exec("cp -R static/albums/* ~/Desktop/");
            Yii::$app->helper->deleteDirectoryFiles('static/albums/');

            echo '---------- '.$music->id.' ----------'."\n";

            unset($file128);
            unset($file320);
        }
    }
}