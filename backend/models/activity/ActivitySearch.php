<?php

namespace backend\models\activity;

use Yii;
use yii\data\ActiveDataProvider;
use common\models\activity\Activity;

class ActivitySearch extends Activity {

    public function rules() {
        // only fields in rules() are searchable
        return [
            [['activity_id'], 'integer'],
            [['created_at', 'target_id', 'ip', 'user_id'], 'safe'],
        ];
    }

    public function search($params) {
        $query = static::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => Yii::$app->helper->getPaginatePerPage(10)],
            'sort' => ['attributes' => ['created_at']]
        ]);

        $dataProvider->sort->defaultOrder = ['created_at' => SORT_DESC];
        // load the seach form data and validate
        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        // adjust the query by adding the filters
        $query->andFilterWhere(['user_id' => $this->user_id])
                ->andFilterWhere(['target_id' => $this->target_id])
                ->andFilterWhere(['activity_id' => $this->activity_id != null ? (int)$this->activity_id : null])
                ->andFilterWhere(['like', 'ip', $this->ip]);

        if (!empty($this->created_at)) {
            if ($range = Yii::$app->helper->getGridFilterRangeTimeStamp($this->created_at)) {
                $query->andFilterWhere([
                    'between', 'created_at',
                    Yii::$app->dateTimeAction->getMongoDate($range[0]),
                    Yii::$app->dateTimeAction->getMongoDate($range[1]),
                ]);
            }
        }

        return $dataProvider;
    }

}
