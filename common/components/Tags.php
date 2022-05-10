<?php

namespace common\components;

use common\models\music\Music;
use common\models\tag\Tag;
use common\models\tag\TagRelation;
use yii\base\Component;

class Tags extends Component {

    public function hashtag($tags, $type, $modelId){


        foreach ($tags as $tag){

            $t = ltrim(rtrim($tag));
            $etc = ['از','با','بر','به','پس','پی','تا','جز','در'];

            if(!array_search($t, $etc)){
                $tagModel = Tag::find()->where(['name' => $t])->one();

                if (!$tagModel){
                    $tagModel = new Tag();
                    $tagModel->name = $t;
                    $tagModel->save();
                }

                if (!TagRelation::find()->where(['post_id' => $modelId, 'tag_id' => $tagModel->id, 'type' => $type])->one()){
                    $tagRelationModel = new TagRelation();
                    $tagRelationModel->post_id = $modelId;
                    $tagRelationModel->tag_id = $tagModel->id;
                    $tagRelationModel->type = $type;
                    $tagRelationModel->save();
                }
            }

        }

    }

    public function createTag($model, $artist){


        $artistName = str_replace(' ', '-', strtolower($artist->name));
        $artistNameFa = str_replace(' ', '-', $artist->name_fa);
        $musicName = str_replace(' ', '-', strtolower($model->name));
        $musicNameFa = str_replace(' ', '-', $model->name_fa);

        if ($model->type == Music::TYPE_MP3){
            $tag =[
//                'دانلود-آهنگ-جدید',
//                'آهنگ-جدید',
                $artistName.'-دانلود-آهنگ-جدید',
                $musicName.'-به-نام-'.$artistName.'-دانلود-آهنگ-جدید',
                'دانلود-آهنگ-جدید-'.$artistNameFa,
                'دانلود-آهنگ-جدید-'.$artistNameFa.'-به-نام-'.$musicNameFa,
                $artistName.'-دانلود-آهنگ',
                $musicName.'-به-نام-'.$artistName.'-دانلود-آهنگ',
                'دانلود-آهنگ-'.$artistNameFa,
                'دانلود-آهنگ-'.$artistNameFa.'-به-نام-'.$musicNameFa,
                'متن-آهنگ-'.$musicNameFa.'-از-'.$artistNameFa,
                'کد-پیشواز-آهنگ-های-'.$artistNameFa,
//                'دانلود-آهنگ-جدید-ایرانی',
//                'download-new-song',
                'download-mp3-'.$artistName.'-'.$musicName,
                'download-mp3-'.$musicName,
                'mp3-download-'.$artistName.'-'.$musicName,
                'mp3-download-'.$musicName,
                'download-new-song-by-'.$artistName.'-called-'.$musicName,
                'download-new-song-'.$artistName.'-'.$musicName,
                $musicName.'-by-'.$artistName,
//                'inavar','ای-نوار','musicplus','موزیک-پلاس','navar','نوار','نوار-موزیک'
                ];
        }elseif ($model->type == Music::TYPE_VIDEO){
            $tag =[
//                'دانلود-موزیک-ویدیو-جدید',
                'دانلود-موزیک-ویدیو-جدید-'.$artistNameFa,
                'دانلود-موزیک-ویدیو-جدید-'.$artistNameFa.'-به-نام-'.$musicNameFa,
                'موزیک-ویدیو-جدید-'.$artistNameFa,
                $musicNameFa.'-دانلود-موزیک-ویدئو-'.$artistNameFa,
                'دانلود-موزیک-ویدیو-'.$artistNameFa.'-به-نام-'.$musicNameFa,
                'دانلود-موزیک-ویدیو-'.$artistNameFa.'-'.$musicNameFa,
                $artistName.'-دانلود-موزیک-ویدیو-جدید',
                $artistName.'-موزیک-ویدیو-جدید',
//                'دانلود-موزیک-ویدیو-جدید-خارجی',
                $musicName.'-دانلود-موزیک-ویدئو-'.$artistName,
                $musicName.'-'.$artistName.'-دانلود-موزیک-ویدیو',
                'download-video-'.$artistName,
                $artistName.'-'.$musicName,
                'video-'.$artistName.'-'.$musicName,
                'download-video-'.$artistName.'-'.$musicName,
                'download-music-video-'.$artistName.'-'.$musicName,
//                'inavar','ای-نوار','musicplus','موزیک-پلاس','navar','نوار','نوار-موزیک'
            ];
        }elseif ($model->type == Music::TYPE_ALBUM){
            $tag =[
//                'دانلود-آلبوم-جدید',
                'دانلود-آلبوم-جدید-'.$artistNameFa,
                'دانلود-آلبوم-جدید-'.$artistNameFa.'-به-نام-'.$musicNameFa,
                'آلبوم-جدید-'.$artistNameFa,
                'دانلود-آلبوم-'.$artistNameFa.'-به-نام-'.$musicNameFa,
//                'دانلود-آلبوم-جدید-خارجی',
                $musicNameFa.'-دانلودآلبوم-'.$artistNameFa,
                'دانلود-آلبوم-'.$artistNameFa.'-'.$musicNameFa,
                $artistName.'-دانلود-آلبوم-جدید',
                $artistName.'-آلبوم-جدید',
                $musicName.'-دانلودآلبوم-'.$artistName,
                $musicName.'-'.$artistName.'-دانلود-آلبوم',
                'download-album-'.$artistName,
                $artistName.'-'.$musicName,
                'album-'.$artistName.'-'.$musicName,
                'download-album-'.$artistName.'-'.$musicName,
//                'inavar','ای-نوار','musicplus','موزیک-پلاس','navar','نوار','نوار-موزیک'
            ];
        }

        return $tag;
    }

    public function createTagFarsi($model, $artist){


        $artistName = str_replace(' ', '-', strtolower($artist->name));
        $artistNameFa = str_replace(' ', '-', $artist->name_fa);
        $musicName = str_replace(' ', '-', strtolower($model->name));
        $musicNameFa = str_replace(' ', '-', $model->name_fa);

        if ($model->type == Music::TYPE_MP3){
            $tag =[
                'دانلود-آهنگ-جدید-خارجی-'.$artistNameFa,
                $musicName.'-به-نام-'.$artistNameFa.'-دانلود-آهنگ-جدید',
                'دانلود-آهنگ-جدید-'.$artistNameFa,
                $artistName.'-دانلود-آهنگ-جدید',
                $musicName.'-به-نام-'.$artistName.'-دانلود-آهنگ',
                'دانلود-آهنگ-جدید-'.$artistNameFa.'-به-نام-'.$musicNameFa,
                'دانلود-آهنگ-'.$artistNameFa.'-به-نام-'.$musicNameFa,
                'متن-آهنگ-'.$musicNameFa.'-از-'.$artistNameFa,
            ];
        }elseif ($model->type == Music::TYPE_VIDEO){
            $tag =[
                'دانلود-موزیک-ویدیو-جدید-خارجی-'.$artistNameFa,
                'دانلود-موزیک-ویدیو-جدید-'.$artistNameFa.'-به-نام-'.$musicNameFa,
                'موزیک-ویدیو-جدید-'.$artistNameFa,
                $musicNameFa.'-دانلود-موزیک-ویدئو-'.$artistNameFa,
                'دانلود-موزیک-ویدیو-'.$artistNameFa.'-به-نام-'.$musicNameFa,
                'دانلود-موزیک-ویدیو-'.$artistNameFa.'-'.$musicNameFa,
                $artistName.'-دانلود-موزیک-ویدیو-جدید',
                $artistName.'-موزیک-ویدیو-جدید',
                $musicName.'-دانلود-موزیک-ویدئو-'.$artistName,
                $musicName.'-'.$artistName.'-دانلود-موزیک-ویدیو',
            ];
        }elseif ($model->type == Music::TYPE_ALBUM){
            $tag =[
                'دانلود-آلبوم-جدید-خارجی-'.$artistNameFa,
                'دانلود-آلبوم-جدید-'.$artistNameFa.'-به-نام-'.$musicNameFa,
                'آلبوم-جدید-'.$artistNameFa,
                'دانلود-آلبوم-'.$artistNameFa.'-به-نام-'.$musicNameFa,
                $musicNameFa.'-دانلودآلبوم-'.$artistNameFa,
                'دانلود-آلبوم-'.$artistNameFa.'-'.$musicNameFa,
                $artistName.'-دانلود-آلبوم-جدید',
                $artistName.'-آلبوم-جدید',
                $musicName.'-دانلودآلبوم-'.$artistName,
                $musicName.'-'.$artistName.'-دانلود-آلبوم',
            ];
        }

        return $tag;
    }
}
