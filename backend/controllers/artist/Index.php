<?php

namespace backend\controllers\artist;

use backend\models\artist\ArtistSearch;
use Yii;
use yii\base\Action;

class Index extends Action {

    public function run() {
        $searchModel = new ArtistSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->get());

        return $this->controller->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

}
