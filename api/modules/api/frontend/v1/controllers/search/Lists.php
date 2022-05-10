<?php

namespace api\modules\api\frontend\v1\controllers\search;

use api\modules\api\frontend\v1\models\search\Search;
use common\models\music\Music;
use yii\base\Action;
use Yii;
use yii\data\Pagination;
use common\models\tag\Tag;

class Lists extends Action {

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

        $tags = Tag::getTypeList();

        foreach ($tags as $key => $value){
            $t[] = [
                'key' => $key,
                'value' => $value
            ];
        };
        return $t;

    }

}
