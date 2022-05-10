<?php

namespace backend\models\artist;

use common\models\artist\Artist;
use Yii;
use yii\data\ActiveDataProvider;

class ArtistSearch extends Artist
{

    public function rules()
    {
        // only fields in rules() are searchable
        return [
            [['status', 'status_fa', 'status_app', 'status_site'], 'integer'],
            [['id', 'created_at', 'name', 'name_fa', 'activity'], 'safe'],
        ];
    }

    public function search($params)
    {
        $query = static::find();

//        $query->select([
//            '_id',
//            'mobile',
//            'username',
//            'email',
//            'status',
//            'avatar',
//            'created_at'
//        ]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => Yii::$app->helper->getPaginatePerPage(10)],
            'sort' => ['attributes' => ['id', 'created_at']]
        ]);

        $dataProvider->sort->defaultOrder = ['created_at' => SORT_DESC];

        // load the seach form data and validate
        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        // adjust the query by adding the filters
        $query->andFilterWhere(['id' => $this->id])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'name_fa', $this->name_fa])
            ->andFilterWhere(['activity' => $this->activity])
            ->andFilterWhere(['status' => $this->status != null ? (int)$this->status : null])
            ->andFilterWhere(['status_fa' => $this->status_fa != null ? (int)$this->status_fa : null])
            ->andFilterWhere(['status_app' => $this->status_app != null ? (int)$this->status_app : null])
            ->andFilterWhere(['status_site' => $this->status_site != null ? (int)$this->status_site : null]);


        if (!empty($this->created_at)) {
            if ($range = Yii::$app->helper->getGridFilterRangeTimeStamp($this->created_at)) {
                $query->andFilterWhere(['between', 'created_at', Yii::$app->date->getMongoDate($range[0]), Yii::$app->date->getMongoDate($range[1])]);
            }
        }

        return $dataProvider;
    }
}