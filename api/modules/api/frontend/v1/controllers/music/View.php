<?php

namespace api\modules\api\frontend\v1\controllers\music;

use common\models\artist\Artist;
use common\models\music\Music;
use common\models\tag\Tag;
use common\models\tag\TagRelation;
use common\models\user\User;
use yii\base\Action;
use Yii;
use common\models\follower\Follower;

class View extends Action {
    
    /**
     * @api {get} /musics/:id Music View
     * @apiVersion 1.0.0
     * @apiName MusicView
     * @apiGroup Music
     * 
     * @apiParam (Params) {String} id Music identity code | Music key_pure
     *
     * 
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
    {
        "music": {
            "id": 4,
            "key": "download-music-aaa2-test",
            "key_fa": "دانلود-آهنگ-aaa2-test",
            "type": {
                "key": 1,
                "value": "Mp3"
            },
            "name": "test",
            "name_fa": "test",
            "artist_id": 24,
            "special": 2,
            "music_id": null,
            "music_no": 0,
            "lyric": "",
            "note": "",
            "note_fa": "",
            "note_app": "",
            "like": 0,
            "like_fa": 0,
            "like_app": 0,
            "view": 0,
            "view_fa": 0,
            "view_app": 10,
            "play": 0,
            "play_fa": 0,
            "play_app": 0,
            "time": "",
            "created_at": 1503450502,
            "directory": "aaa2",
            "dl_link": "saman - test",
            "image": "saman - test.jpg",
            "key_pure": "aaa2-test",
            "artist": {
                "id": 24,
                "name": "saman",
                "name_fa": "saman",
                "activity": "Singer",
                "image": "aaa2.jpg",
                "like": 0,
                "like_fa": 0,
                "like_app": 0,
                "status": 1,
                "status_fa": 1,
                "status_app": 1,
                "key": "aaa2",
                "key_fa": "aaa2",
                "link": "/f1/artists/aaa2"
            },
            "link": {
                "128": "http://localhost/navar/backend/web/upload/aaa2/mp3/saman - test[128].mp3",
                "320": "http://localhost/navar/backend/web/upload/aaa2/mp3/saman - test[320].mp3"
            }
        },
        "artists": {
            "singer": [
                {
                    "id": 24,
                    "name": "saman",
                    "name_fa": "saman",
                    "activity": "Singer",
                    "image": "aaa2.jpg",
                    "like": 0,
                    "like_fa": 0,
                    "like_app": 0,
                    "status": 1,
                    "status_fa": 1,
                    "status_app": 1,
                    "key": "aaa2",
                    "key_fa": "aaa2",
                    "link": "/f1/artists/aaa2"
                }
            ]
        },
        "related_music": [
            {
                "id": 1037,
                "key": "alireza-talischi-175",
                "key_fa": "دانلود-آهنگ-علیرضا-طلیسچی-175",
                "type": {
                    "key": 1,
                    "value": "Mp3"
                },
                "name": "175",
                "name_fa": "175",
                "artist_id": 1919,
                "special": 1,
                "music_id": null,
                "music_no": 0,
                "lyric": "یه ماهی داشت می خواست آزاد باشه\r\n\r\nمی خواست دریا بره شاد باشه\r\n\r\nمیون شون یکم دوری فقط بود\r\n\r\nولی دریام یکم بی معرفت بود\r\n\r\n\r\nدنبال ماهی تا دریا رسیده\r\n\r\nاما دریا هم ازش قطع امید\r\n\r\nهر کی برگشته نشونی شو نداره\r\n\r\nیکی می گفت دیدتش بی قراره\r\n\r\n\r\nمادری که جز خدا راهی نداره\r\n\r\nاحتیاجی به شناسایی نداره\r\n\r\nچون قرار توی انتظار بمیره\r\n\r\nبایدم عشق شو اشتباه بگیره\r\n\r\n\r\nبوشو حس می کنه تو همین حوالی\r\n\r\nگریه می کنه واسه یه تنگ خالی\r\n\r\nمی سپره پیداش کنن می شینه هر جا\r\n\r\nخونشو می بره تا نزدیک دریا\r\n\r\n\r\nبی هوا براش می خواد غذا بریزه\r\n\r\nهرکسی شبیه شه خیلی عزیزه\r\n\r\nبس که تو دلش پر از غصه و درد\r\n\r\nدرد زانو هاشو هیچ وقت حس نکرده\r\n\r\n\r\nمادری که جز خدا راهی نداره\r\n\r\nاحتیاجی به شناسایی نداره\r\n\r\nچون قرار تو انتظار بمیره\r\n\r\nبایدم عشق شو اشتباه بگیره",
                "note": null,
                "note_fa": "",
                "note_app": null,
                "like": 12,
                "like_fa": 0,
                "like_app": 0,
                "view": 1279,
                "view_fa": 173,
                "view_app": 0,
                "play": 0,
                "play_fa": 0,
                "play_app": 0,
                "time": null,
                "directory": "alireza-talischi",
                "dl_link": "Alireza Talischi - 175",
                "image": "Alireza Talischi - 175.jpg",
                "created_at": 1441983637,
                "key_pure": "alireza-talischi-175",
                "title_en": "Download New Song By ",
                "title_fa": "دانلود آهنگ جدید ",
                "artist": {
                    "id": 1919,
                    "name": "alireza talischi",
                    "name_fa": "علیرضا طلیسچی",
                    "activity": "Singer",
                    "image": "alireza-talischi.jpg",
                    "like": 6,
                    "like_fa": 0,
                    "like_app": 0,
                    "status": 1,
                    "status_fa": 1,
                    "status_app": 1,
                    "key": "alireza-talischi",
                    "key_fa": "علیرضا-طلیسچی",
                    "link": "/f1/artists/alireza-talischi"
                },
                "link": {
                    "128": "http://localhost/navar/backend/web/upload/alireza-talischi/mp3/Alireza Talischi - 175[128].mp3",
                    "320": "http://localhost/navar/backend/web/upload/alireza-talischi/mp3/Alireza Talischi - 175[320].mp3"
                }
            }
        ]
    }
     *
     *
     * @apiError (Error 401)Unauthorized Login required
     *
     * @apiError (Error 404)NotFound Type required
     */
    public function run($id) {



        $music = $this->controller->findModel($id, Yii::$app->request->headers->get('type'));

        $updateModel = [];
        $artists = $this->controller->findModelArtists($music->id);

        foreach ($artists as $artist) {

            if ($artist->activity == Artist::TYPE_SINGER || $artist->activity == Artist::TYPE_MAIN_ARTIST) {
                $updateModel['singer'][] = $artist->artist;
            } elseif ($artist->activity == Artist::TYPE_COMPOSER) {
                $updateModel['composer'][] = $artist->artist;
            } elseif ($artist->activity == Artist::TYPE_LYRIC) {
                $updateModel['lyric'][] = $artist->artist;
            } elseif ($artist->activity == Artist::TYPE_REGULATOR) {
                $updateModel['regulator'][] = $artist->artist;
            } elseif ($artist->activity == Artist::TYPE_MUSICIANS) {
                $updateModel['musician'][] = $artist->artist;
            } elseif ($artist->activity == Artist::TYPE_MONTAGE) {
                $updateModel['montage'][] = $artist->artist;
            } elseif ($artist->activity == Artist::TYPE_DIRECTOR) {
                $updateModel['director'][] = $artist->artist;
            }

        }


        $relatedMp3 = '';
        if ($music->type == Music::TYPE_MP3){
            $relatedMusic = Music::find()->where(['artist_id' => $music->artist_id, 'type' => Music::TYPE_MP3])->andWhere(['<>', 'id', $music->id])->limit(15)->all();
        }elseif($music->type == Music::TYPE_ALBUM){
            $relatedMusic = Music::find()->where(['music_id' => $music->id])->orderBy(['music_no' => SORT_ASC])->all();
        }else{
            $relatedMusic = Music::find()->where(['artist_id' => $music->artist_id, 'type' => Music::TYPE_VIDEO])->andWhere(['<>', 'id', $music->id])->limit(15)->all();
            $relatedMp3 = Music::find()->where(['id' => $music->music_id])->one();
        }

        $follower = null;
        $authHeader = Yii::$app->request->headers->get('Authorization');
        if ($authHeader){
            preg_match('/^Bearer\s+(.*?)$/', $authHeader, $matches);
            $identity = User::findIdentityByAccessToken($matches[1]);
            $follower = Follower::find()->where(['post_id' => $id, 'user_id' => $identity ? $identity->id : false, 'post_type' => Follower::TYPE_MUSIC])->one();
        }

        return [
            'music' => $music,
            'related_music' => $relatedMusic,
            'related_mp3' => $relatedMp3,
            'artists' => $updateModel,
            'tags' => TagRelation::find()->select('tag_id')->where(['post_id' => $music->id, 'type' => [Tag::TYPE_MP3,Tag::TYPE_VIDEO, Tag::TYPE_ALBUM, Tag::TYPE_MUSIC]])->all(),
            'follow' =>  !is_null($follower) ? true : false,
        ];

    }

}
