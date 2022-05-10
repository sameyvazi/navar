<?php

namespace backend\models\tag;

use common\models\artist\Artist;
use common\models\tag\Tag;
use Yii;
use yii\data\ActiveDataProvider;

class TagSearch extends Tag
{

    public function rules()
    {
        // only fields in rules() are searchable
        return [
            ['name', 'safe']
        ];
    }

    public function search($params)
    {
        $query = static::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => Yii::$app->helper->getPaginatePerPage(10)],
            'sort' => ['attributes' => ['id']]
        ]);

        //$dataProvider->sort->defaultOrder = ['created_at' => SORT_DESC];

        // load the seach form data and validate
        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        // adjust the query by adding the filters
        $query->andFilterWhere(['id' => $this->id])
            ->andFilterWhere(['like', 'name', $this->name]);


        if (!empty($this->created_at)) {
            if ($range = Yii::$app->helper->getGridFilterRangeTimeStamp($this->created_at)) {
                $query->andFilterWhere(['between', 'created_at', Yii::$app->date->getMongoDate($range[0]), Yii::$app->date->getMongoDate($range[1])]);
            }
        }

        return $dataProvider;
    }
}