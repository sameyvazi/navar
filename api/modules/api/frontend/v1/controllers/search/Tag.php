<?php

namespace api\modules\api\frontend\v1\controllers\search;

use api\modules\api\frontend\v1\models\search\SearchMusic;
use api\modules\api\frontend\v1\models\search\SearchTag;
use yii\base\Action;

class Tag extends Action {

    /**
     * @api {get} /searches/tag Search Music
     * @apiVersion 1.0.0
     * @apiName SearchIndex
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

    public function run($id) {

        return (new SearchTag)->search($id);

    }

}
