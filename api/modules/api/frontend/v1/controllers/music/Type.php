<?php

namespace api\modules\api\frontend\v1\controllers\music;

use yii\base\Action;
use common\models\music\Music;

class Type extends Action {
    
    /**
     * @api {get} /musics/type Music Type
     * @apiVersion 1.0.0
     * @apiName MusicType
     * @apiGroup Music
     *
     * 
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
    [
        {
            "key": 1,
            "value": "Mp3"
        },
        {
            "key": 2,
            "value": "ویدیو"
        },
        {
            "key": 3,
            "value": "Album"
        }
    ]
     *
     *
     * @apiError (Error 401)Unauthorized Login required
     */
    public function run() {

        //chdir('../../../../navar_en/public_html/web/storage/media/ehsan-khaje-amiri/video/');
        //$a = file_exists('Ehsan Khaje Amiri - 30 Salegi 1080p [iNavar.com].mp4');

        return getcwd();

        $musics = Music::getTypeList();

        foreach ($musics as $key => $value){
            $type[] = [
                'key' => $key,
                'value' => $value
            ];
        }
        return $type;

    }

}
