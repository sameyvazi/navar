<?php

namespace backend\models\mood;

use common\models\artist\Artist;
use common\models\mood\Mood;
use Yii;
use yii\data\ActiveDataProvider;

class MoodSearch extends Mood
{

    public function rules()
    {
        // only fields in rules() are searchable
        return [
            [['id', 'no'], 'integer'],
            [['name'], 'safe'],
        ];
    }

    public function search($params)
    {
        $query = static::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => Yii::$app->helper->getPaginatePerPage(10)],
            'sort' => ['attributes' => ['id', 'created_at', 'no']]
        ]);

        $dataProvider->sort->defaultOrder = ['created_at' => SORT_DESC];

        // load the seach form data and validate
        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        // adjust the query by adding the filters
        $query->andFilterWhere(['id' => $this->id])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['no' => $this->no != null ? (int)$this->no : null]);


        if (!empty($this->created_at)) {
            if ($range = Yii::$app->helper->getGridFilterRangeTimeStamp($this->created_at)) {
                $query->andFilterWhere(['between', 'created_at', Yii::$app->date->getMongoDate($range[0]), Yii::$app->date->getMongoDate($range[1])]);
            }
        }

        return $dataProvider;
    }
}