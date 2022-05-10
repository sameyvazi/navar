<?php

namespace backend\models\playlistMusic;

use common\models\playlist\PlaylistMusic;
use Yii;
use yii\data\ActiveDataProvider;

class PlaylistMusicSearch extends PlaylistMusic
{

    public function rules()
    {
        // only fields in rules() are searchable
        return [
            [['id', 'playlist_id', 'music_id'], 'integer'],
        ];
    }

    public function search($params)
    {
        $query = static::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => Yii::$app->helper->getPaginatePerPage(10)],
            'sort' => ['attributes' => ['id', 'playlist_id', 'no']]
        ]);

        $dataProvider->sort->defaultOrder = ['no' => SORT_ASC];

        // load the seach form data and validate
        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        // adjust the query by adding the filters
        $query->andFilterWhere(['id' => $this->id])
            ->andFilterWhere(['playlist_id' => $this->playlist_id != null ? (int)$this->playlist_id : null])
            ->andFilterWhere(['music_id' => $this->music_id != null ? (int)$this->music_id : null]);


        if (!empty($this->created_at)) {
            if ($range = Yii::$app->helper->getGridFilterRangeTimeStamp($this->created_at)) {
                $query->andFilterWhere(['between', 'created_at', Yii::$app->date->getMongoDate($range[0]), Yii::$app->date->getMongoDate($range[1])]);
            }
        }

        return $dataProvider;
    }
}