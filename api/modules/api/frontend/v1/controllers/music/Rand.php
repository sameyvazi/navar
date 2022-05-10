<?php

namespace api\modules\api\frontend\v1\controllers\music;

use api\modules\api\frontend\v1\models\music\MusicSearch;
use common\models\music\Music;
use yii\base\Action;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\Expression;

class Rand extends Action {


    public function run() {

        $status = \Yii::$app->helper->getTypeStatus(Yii::$app->request->headers->get('type'));

        $query = Music::find()->where([$status => Music::STATUS_ACTIVE, 'music_no' => 0])
            ->orderBy(new Expression('rand()'))
            ->limit(8);


//        return $query;
//
//
//
//        $query = static::find()->where([$status => Music::STATUS_ACTIVE, 'music_no' => 0]);

//        $query = static::find()->where(['and',
//            $status => Music::STATUS_ACTIVE,
//            ['=', 'type', Music::TYPE_MP3],
//            ['music_id' => null]
//        ])->orWhere(['and',
//            $status => Music::STATUS_ACTIVE,
//            ['type' => Music::TYPE_VIDEO],
//        ])->orWhere(['and',
//            $status => Music::STATUS_ACTIVE,
//            ['type' => Music::TYPE_ALBUM],
//        ]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => Yii::$app->helper->getPaginatePerPage(50)],
            'sort' => ['attributes' => ['id', 'created_at', 'like', 'like_fa', 'like_app', 'view', 'view_fa', 'view_app', 'play', 'play_fa', 'play_app', 'created_at']]
        ]);

        $dataProvider->sort->defaultOrder = ['created_at' => SORT_DESC];

        return $dataProvider;



    }

}
