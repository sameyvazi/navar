<?php

namespace backend\controllers\activity;

use common\models\activity\Activity;
use Yii;
use yii\base\Action;
use backend\models\activity\ActivitySearch;

class Index extends Action {

    public function run() {
        $searchModel = new ActivitySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->get());
        return $this->controller->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,

        ]);
    }

}
