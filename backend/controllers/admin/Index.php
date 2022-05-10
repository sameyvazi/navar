<?php

namespace backend\controllers\admin;

use Yii;
use yii\base\Action;
use backend\models\admin\AdminSearch;

class Index extends Action {

    public function run() {
        $searchModel = new AdminSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->get());

        return $this->controller->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

}
