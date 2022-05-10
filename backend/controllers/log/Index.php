<?php

namespace backend\controllers\log;

use Yii;
use yii\base\Action;
use backend\models\log\LogSearch;

class Index extends Action {

    public function run() {
        $searchModel = new LogSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->get());

        return $this->controller->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

}
