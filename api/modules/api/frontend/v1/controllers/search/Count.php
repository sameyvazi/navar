<?php

namespace api\modules\api\frontend\v1\controllers\search;

use common\models\tag\Tag;
use yii\base\Action;

class Count extends Action {

    public function run() {

        return Tag::find()->count();

    }

}
