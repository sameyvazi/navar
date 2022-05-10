<?php

namespace backend\models\like;

use common\models\comment\Comment;
use common\models\like\Like;
use Yii;
use yii\data\ActiveDataProvider;

class LikeSearch extends Like
{

    public function rules()
    {
        // only fields in rules() are searchable
        return [
            [['status'], 'integer'],
            [['id', 'created_at'], 'safe'],
        ];
    }

    public function search($params)
    {
        $query = static::find();


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
        $query->andFilterWhere(['id' => $this->id]);


        if (!empty($this->created_at)) {
            if ($range = Yii::$app->helper->getGridFilterRangeTimeStamp($this->created_at)) {
                $query->andFilterWhere(['between', 'created_at', Yii::$app->date->getMongoDate($range[0]), Yii::$app->date->getMongoDate($range[1])]);
            }
        }

        return $dataProvider;
    }
}