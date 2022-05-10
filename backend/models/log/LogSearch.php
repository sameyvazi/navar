<?php

namespace backend\models\log;

use Yii;
use yii\data\ActiveDataProvider;
use backend\models\log\Log;

class LogSearch extends Log {

    public function rules() {
        // only fields in rules() are searchable
        return [
            [['level'], 'integer'],
            [['category', 'log_time'], 'safe'],
        ];
    }

    public function search($params) {
        $query = static::find();
        
        $query->select([
            '_id',
            'level',
            'category',
            'prefix',
            'log_time'
        ]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => Yii::$app->helper->getPaginatePerPage(10)],
            'sort' => ['attributes' => ['log_time', 'level']]
        ]);

        $dataProvider->sort->defaultOrder = ['log_time' => SORT_DESC];
        // load the seach form data and validate
        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        // adjust the query by adding the filters
        $query->andFilterWhere(['_id' => $this->_id]);
        $query->andFilterWhere(['level' => $this->level]);
        $query->andFilterWhere(['like', 'category', $this->category]);

        if (!empty($this->log_time)) {
            if ($range = Yii::$app->helper->getGridFilterRangeTimeStamp($this->log_time)) {
                $query->andFilterWhere(['between', 'log_time', (int)$range[0], (int)$range[1]]);
            }
        }

        return $dataProvider;
    }

}
