<?php

namespace backend\controllers\like;

use backend\models\like\LikeSearch;
use Yii;
use yii\base\Action;

class Index extends Action {

    public function run() {
        $searchModel = new LikeSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->get());

        return $this->controller->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

}
