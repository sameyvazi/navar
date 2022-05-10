<?php

namespace backend\controllers\playlistMusic;


use backend\models\playlistMusic\PlaylistMusicSearch;
use Yii;
use yii\base\Action;

class Index extends Action {

    public function run() {
        $searchModel = new PlaylistMusicSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->get());

        return $this->controller->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

}
