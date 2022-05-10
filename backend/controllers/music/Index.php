<?php

namespace backend\controllers\music;

use backend\models\music\MusicSearch;
use Yii;
use yii\base\Action;

class Index extends Action {

    public function run() {
        $searchModel = new MusicSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->get());

        return $this->controller->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

}
