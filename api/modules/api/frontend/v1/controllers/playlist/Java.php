<?php

namespace api\modules\api\frontend\v1\controllers\playlist;

use yii\base\Action;
use common\models\music\Music;
use Yii;

class Java extends Action {

    public function run($id) {


        $music = Music::find()->where(['id' => $id])->one();

        $musicId = $music->id;

        if(@get_headers("http://dl.inavar.xyz/media/playlists/$musicId.js")[0] != "HTTP/1.1 200 OK"){

            if($music->type == Music::TYPE_MP3){


                $address = ($this->makeAddress($music));

                if(@get_headers($address['96'])[0] == "HTTP/1.1 200 OK"){
                    $file = $address['96'];
                }elseif(@get_headers($address['128'])[0] == "HTTP/1.1 200 OK"){
                    $file = $address['128'];
                }else{
                    $file = $address['320'];
                }

                $myfile = fopen("upload/playlists/$musicId.js", "w") or die("Unable to open file!");

                $txt = "$(document).ready(function(){
                    new jPlayerPlaylist({
                        jPlayer: '#jquery_jplayer_1',
                        cssSelectorAncestor: '#jp_container_1'
                    }, [\n";
                fwrite($myfile, $txt);


                $txt = "{
                    title:'".ucwords($music->name)."',
                    artist:'".ucwords($music->artist_name)."',
                    mp3:'".$file."',
                        },\n";
                fwrite($myfile, $txt);

                $txt = "], {
                    swfPath: '/dist/jplayer',
                    supplied: 'mp3',
                    wmode: 'window',
                    useStateClassSkin: true,
                    autoBlur: false,
                    smoothPlayBar: true,
                    keyEnabled: true
                        });
                    });";
                fwrite($myfile, $txt);
                fclose($myfile);

                $b [] = [
                    'name' => $music->name,
                    'artist_name' => $music->artist_name,
                    'file128' => $address['128'],
                    'file320' => $address['320'],
                ];

                $ftp = \Yii::$app->helper->ftpLogin();
                $ftp->putAll('upload/playlists', 'playlists', FTP_BINARY);
                \Yii::$app->helper->deleteDirectoryFiles('upload/playlists/');

            }elseif ($music->type == Music::TYPE_ALBUM){

                $musics = Music::find()->where(['music_id' => $music->id, 'type' => Music::TYPE_MP3])->all();

                $myfile = fopen("upload/playlists/$musicId.js", "w") or die("Unable to open file!");

                $txt = "$(document).ready(function(){
                    new jPlayerPlaylist({
                        jPlayer: '#jquery_jplayer_1',
                        cssSelectorAncestor: '#jp_container_1'
                    }, [\n";
                fwrite($myfile, $txt);


                foreach ($musics as $m){

                    $address = ($this->makeAddress($m));

                    if(@get_headers($address['96'])[0] == "HTTP/1.1 200 OK"){
                        $file = $address['96'];
                    }elseif(@get_headers($address['128'])[0] == "HTTP/1.1 200 OK"){
                        $file = $address['128'];
                    }else{
                        $file = $address['320'];
                    }

                    $txt = "{
                    title:'".ucwords($m->name)."',
                    artist:'".ucwords($m->artist_name)."',
                    mp3:'".$file."',
                        },\n";
                    fwrite($myfile, $txt);

                    $b [] = [
                        'name' => $m->name,
                        'artist_name' => $m->artist_name,
                        'file128' => $address['128'],
                        'file320' => $address['320'],
                    ];

                }

                $txt = "], {
                    swfPath: '/dist/jplayer',
                    supplied: 'mp3',
                    wmode: 'window',
                    useStateClassSkin: true,
                    autoBlur: false,
                    smoothPlayBar: true,
                    keyEnabled: true
                        });
                    });";
                fwrite($myfile, $txt);
                fclose($myfile);

                $ftp = \Yii::$app->helper->ftpLogin();
                $ftp->putAll('upload/playlists', 'playlists', FTP_BINARY);
                \Yii::$app->helper->deleteDirectoryFiles('upload/playlists/');

                $address = ($this->makeAddress($music));

                $albumName = $music->name;
                $albumArtistName = $music->artist_name;
                $zip128 = $address['128'];
                $zip320 = $address['320'];

            }
        }elseif ($music->type == Music::TYPE_MP3){

            $address = ($this->makeAddress($music));

            $b [] = [
                'name' => $music->name,
                'artist_name' => $music->artist_name,
                'file128' => $address['128'],
                'file320' => $address['320'],
            ];

        }elseif ($music->type == Music::TYPE_ALBUM){

            $musics = Music::find()->where(['music_id' => $music->id, 'type' => Music::TYPE_MP3])->all();

            foreach ($musics as $m){

                $address = ($this->makeAddress($m));

                $b [] = [
                    'name' => $m->name,
                    'artist_name' => $m->artist_name,
                    'file128' => $address['128'],
                    'file320' => $address['320'],
                ];

                $address = ($this->makeAddress($music));

                $albumName = $music->name;
                $albumArtistName = $music->artist_name;
                $zip128 = $address['128'];
                $zip320 = $address['320'];

            }
        }

        $music->updateCounters(['view_app' => 1]);

        return [
            'js_file' => "http://dl.inavar.ir/media/playlists/$musicId.js",
            'album_name' => isset($albumName) ? $albumName : null,
            'album_artist_name' => isset($albumArtistName) ? $albumArtistName : null,
            'image' => Yii::$app->params['storageServerUrl2'].$music['directory'].'/cover/'.'300'.$music['image'],
            'zip128' => isset($zip128) ? $zip128 : null,
            'zip320' => isset($zip320) ? $zip320 : null,
            'info' => $b
        ];

    }

    protected function makeAddress($model){

        if ($model->type == Music::TYPE_MP3){

            $albumKey = '';
            if ($model->music_id != null && $music = Music::find()->where(['id' => $model->music_id])->one()){

                if($music->type == Music::TYPE_ALBUM && $music->id > 6700){
                    $albumKey = $music->key_pure.'/';
                }
            }

            return [
                '320' => Yii::$app->params['storageServerUrl2'].$model->artist->key.'/mp3/'.$albumKey.rawurlencode($model->dl_link.' [320].mp3'),
                '128' => Yii::$app->params['storageServerUrl2'].$model->artist->key.'/mp3/'.$albumKey.rawurlencode($model->dl_link.' [128].mp3'),
                '96' => Yii::$app->params['storageServerUrl2'].$model->artist->key.'/mp3/'.$albumKey.rawurlencode($model->dl_link.' [96].mp3'),
            ];

        }elseif ($model->type = Music::TYPE_ALBUM){

            $albumKey = '';

            if($model->type == Music::TYPE_ALBUM && $model->id > 6700){
                $albumKey = $model->key_pure.'/';
            }

            return[
                '320' => Yii::$app->params['storageServerUrl2'].$model->artist->key.'/mp3/'.$albumKey.rawurlencode($model->dl_link.' [320].zip'),
                '128' => Yii::$app->params['storageServerUrl2'].$model->artist->key.'/mp3/'.$albumKey.rawurlencode($model->dl_link.' [128].zip'),
            ];
        }
    }
}
