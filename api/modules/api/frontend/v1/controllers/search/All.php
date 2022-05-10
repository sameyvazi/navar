<?php

namespace api\modules\api\frontend\v1\controllers\search;

use common\models\tag\Tag;
use yii\base\Action;

class All extends Action {

    public function run($id) {

        return Tag::find()->select(['name', 'created_at'])->limit(5000)->offset($id)->all();

    }

}
