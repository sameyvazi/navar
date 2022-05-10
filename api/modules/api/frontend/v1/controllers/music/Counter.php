<?php

namespace api\modules\api\frontend\v1\controllers\music;

use common\models\artist\Artist;
use common\models\music\Music;
use common\models\tag\Tag;
use common\models\tag\TagRelation;
use yii\base\Action;
use Yii;

class Counter extends Action {
    

    public function run($id) {


        $type = Yii::$app->request->headers->get('type');

        $music = $this->controller->findModel($id, $type);

        $view = \Yii::$app->helper->getTypeView($type);

        return [
            'view' => $music->$view,
        ];

    }

}
