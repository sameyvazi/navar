<?php

namespace backend\controllers\mood;

use backend\models\mood\MoodSearch;
use Yii;
use yii\base\Action;

class Index extends Action {

    public function run() {
        $searchModel = new MoodSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->get());

        return $this->controller->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

}
