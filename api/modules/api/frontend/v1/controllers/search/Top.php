<?php

namespace api\modules\api\frontend\v1\controllers\search;

use common\models\log\LogSearch;
use yii\base\Action;

class Top extends Action {

    /**
     * @api {get} /searches/top Top Search
     * @apiVersion 1.0.0
     * @apiName SearchTop
     * @apiGroup Search
     *
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *
     * @apiError (Error 401)Unauthorized Login required
     *
     * @apiError (Error 404)NotFound Type required
     */

    public function run() {

        return LogSearch::find()->select('*, COUNT(*) as nr')->groupBy(['query'])->orderBy(['nr' => SORT_DESC])->limit(5)->all();
    }

}
