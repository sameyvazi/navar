<?php

namespace console\controllers;


use common\models\artist\Artist as Artists;
use common\models\music\Music;
use common\models\music\MusicArtist;
use yii\console\Controller;
use Yii;
use yii\helpers\FileHelper;

class TestController extends Controller {
    
//    public function actionSms($mobile)
//    {
//        Yii::$app->sms->send($mobile, 'just for test');
//    }
//
//    public function actionArtist()
//    {
//        $artists = Artists::find()->where(['=', 'id', 1873])->all();
//
//        foreach ($artists as $artist){
//
//            $ftp = Yii::$app->helper->ftpLogin();
//
//            $ftp->mkdir($artist->key);
//            $ftp->mkdir($artist->key . '/mp3');
//            $ftp->mkdir($artist->key . '/video');
//            $ftp->mkdir($artist->key . '/cover');
//
//            //$path = '/Applications/MAMP/htdocs/navar/backend/web/upload/';
//            $path = '/var/www/inavar.ir/public_html/panel/upload/';
//
//            $ftp->putAll($path.$artist->key.'/cover/', $artist->key.'/cover/', FTP_BINARY);
//            $ftp->putAll($path.$artist->key.'/mp3/', $artist->key.'/mp3/', FTP_BINARY);
//            $ftp->putAll($path.$artist->key.'/video/', $artist->key.'/video/', FTP_BINARY);
//
//            echo $artist->id.'-';
//        }
//    }
//

    public function actionArtist2()
    {
        $artists = Artists::find()->all();

        foreach ($artists as $artist){

//            $path = '/Applications/MAMP/htdocs/navar/backend/web/upload/';
            $path = '/home/musicplus/www/navar/backend/web/upload/';

            $this->createDirectory($path.$artist->key, 0775,true);
            $this->createDirectory($path.$artist->key . '/mp3', 0775, true);
            $this->createDirectory($path.$artist->key . '/video', 0775, true);
            $this->createDirectory($path.$artist->key . '/cover', 0775, true);

            echo $artist->id.'-';

        }
    }

    protected function createDirectory($address, $mode, $recursive){

        FileHelper::createDirectory($address, $mode, $recursive);
        return;

    }

    public function actionArtistName()
    {

        for ($i=7242;$i<=7263;$i++){
            $m = Music::find()->where(['id' => $i])->one();

            $artist = Artists::find()->where(['id' => $m['artist_id']])->one();

            $m->artist_name = $artist['name'];
            $m->artist_name_fa = $artist['name_fa'];
            $m->save();

            echo $m['id'].'-';
        }
    }

    public function actionAlbum($id){


        set_time_limit(0);

        $albums = Music::find()
            ->where(['type' => Music::TYPE_ALBUM, 'id' => (int)$id])
            //->andWhere(['>', 'id', 6700])
            ->all();


        foreach ($albums as $album){

            $musics = Music::find()->where(['music_id' => $album->id, 'type' => Music::TYPE_MP3])->all();


            $create128 = false;

            foreach ($musics as $music){

                //var_dump($music);die;

                //dl 320

                $file320 [] = 'static/albums/'.$music->dl_link.' [320].mp3';
                $url = "http://static.musicplus.info/storage/media/"."$music->directory"."/mp3/".$album->key."/".rawurlencode($music->dl_link.' [320].mp3');

                if(@get_headers($url)[0] == "HTTP/1.1 200 OK"){

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
                $url = "http://static.musicplus.info/storage/media/"."$music->directory"."/mp3/".$album->key."/".rawurlencode($music->dl_link.' [128].mp3');

                if(@get_headers($url)[0] == "HTTP/1.1 200 OK"){

                    $fp = fopen ('static/albums/'.$music->dl_link.' [128].mp3', 'w+');
                    $ch = curl_init(str_replace(" ","%20",$url));
                    curl_setopt($ch, CURLOPT_TIMEOUT, 50);
                    curl_setopt($ch, CURLOPT_FILE, $fp);
                    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                    $data = curl_exec($ch);
                    curl_close($ch);

                }elseif(file_exists('static/albums/'.$music->dl_link.' [320].mp3')){


                    $music->dl_link = str_replace("(","\(",$music->dl_link);
                    $music->dl_link = str_replace(")","\)",$music->dl_link);
                    //convert 320 to 128
                    $fileName = str_replace(" ","\ ",$music->dl_link).'\ [320].mp3';
                    $fileNameNew = str_replace(" ","\ ",$music->dl_link).'\ [128].mp3';
                    $fileNameNew96 = str_replace(" ","\ ",$music->dl_link).'\ [96].mp3';

                    exec("ffmpeg -i static/albums/$fileName -ab 128000 static/albums/$fileNameNew");
                    exec("ffmpeg -i static/albums/$fileName -ab 96000 static/albums/$fileNameNew96");


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
            $ftp->putAll('static/albums/', $music->directory."/mp3/".$album->key, FTP_BINARY);
            Yii::$app->helper->deleteDirectoryFiles('static/albums/');




            die;



        }

    }

    public function actionType(){

        ini_set('memory_limit', '-1');
        $musicArtists = MusicArtist::find()->all();

        foreach ($musicArtists as $musicArtist){

            $music = Music::find()->select('type')->where(['id' => $musicArtist['music_id']])->one();

            $musicArtist->type = $music->type;
            $musicArtist->save();

            echo $musicArtist->id . '-';

        }

    }

    public function actionSendMsg(){
        // API access key from Google API's Console
        define( 'API_ACCESS_KEY', 'AAAAxRH-zLE:APA91bEGOTvNee5h9iQPJeDpc0oNqOqWDzB0N5WNYI72CfrBpWiK9CmmgMruuekCXGZo8CpbRO_nAeWaY5DxRegOYx0a8N8h12E7fQRFtiXjfRatv8n1M6Bzgehpze0K2CrzKSFmdCTY' );
        $registrationIds = array( '' );
// prep the bundle
        $msg = array
        (
            'message' 	=> 'here is a message. message',
            'title'		=> 'This is a title. title',
            'subtitle'	=> 'This is a subtitle. subtitle',
            'tickerText'	=> 'Ticker text here...Ticker text here...Ticker text here',
            'vibrate'	=> 1,
            'sound'		=> 1,
            'largeIcon'	=> 'large_icon',
            'smallIcon'	=> 'small_icon'
        );
        $fields = array
        (
            //'registration_ids' 	=> $registrationIds,
            //'gmpAppId' 	=> '1:846410468529:android:b8819a30bbad357a',
            'data'			=> $msg
        );

        $headers = array
        (
            'Authorization: key=' . API_ACCESS_KEY,
            'Content-Type: application/json'
        );

        $ch = curl_init();
        curl_setopt( $ch,CURLOPT_URL, 'https://android.googleapis.com/gcm/send' );
        curl_setopt( $ch,CURLOPT_POST, true );
        curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
        curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
        curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
        $result = curl_exec($ch );
        curl_close( $ch );
        echo $result;
    }
}