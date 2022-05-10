<?php

namespace api\modules\api\frontend\v1\controllers\search;

use api\modules\api\frontend\v1\models\search\Search;
use yii\base\Action;
use Yii;
use yii\data\Pagination;

class Index extends Action {

    /**
     * @api {get} /searches/:id Search Music and Artist
     * @apiVersion 1.0.0
     * @apiName SearchView
     * @apiGroup Search
     *
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *
     * @apiError (Error 404)NotFound Type required
     */

    public function run() {

        return (new Search)->search(Yii::$app->getRequest()->get());

    }

}
