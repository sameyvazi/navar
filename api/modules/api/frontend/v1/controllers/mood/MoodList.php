<?php

namespace api\modules\api\frontend\v1\controllers\mood;

use common\models\artist\Artist;
use common\models\mood\Mood;
use yii\base\Action;
use Yii;
use common\models\music\Music;

class MoodList extends Action {
    
    /**
     * @api {get} /moods/list Mood List
     * @apiVersion 1.0.0
     * @apiName MoodList
     * @apiGroup Mood
     *
     * 
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
    [
        {
            "id": 2,
            "name": "acasc",
            "name_fa": "cacsdc"
        },
        {
            "id": 3,
            "name": "aaaa",
            "name_fa": "aaaa"
        }
    ]
     *
     *
     * @apiError (Error 401)Unauthorized Login required
     *
     * @apiError (Error 404)NotFound Type required
     *
     */
    public function run() {

        $status = \Yii::$app->helper->getTypeStatus(Yii::$app->request->headers->get('type'));

        $moods = Mood::find()->where([$status => Mood::STATUS_ACTIVE])->andWhere(['<>', 'id', Yii::$app->params['moodIdUsers']])->all();

        $m = [];
        foreach ($moods as $mood){
            $m[] = [
                'id' => $mood->id,
                'name' => $mood->name,
                'name_fa' => $mood->name_fa,
            ];
        }
        return $m;

    }

}
