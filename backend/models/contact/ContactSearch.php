<?php

namespace backend\models\contact;

use common\models\contact\Contact;
use Yii;
use yii\data\ActiveDataProvider;

class ContactSearch extends Contact
{

    public function rules()
    {
        // only fields in rules() are searchable
        return [
            [['status', 'type'], 'integer'],
            [['id', 'created_at', 'subject'], 'safe'],
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
        $query->andFilterWhere(['id' => $this->id])
            ->andFilterWhere(['status' => $this->status != null ? (int)$this->status : null])
            ->andFilterWhere(['like', 'subject', $this->subject != null ? $this->subject : null])
            ->andFilterWhere(['type' => $this->type != null ? (int)$this->type : null]);


        if (!empty($this->created_at)) {
            if ($range = Yii::$app->helper->getGridFilterRangeTimeStamp($this->created_at)) {
                $query->andFilterWhere(['between', 'created_at', Yii::$app->date->getMongoDate($range[0]), Yii::$app->date->getMongoDate($range[1])]);
            }
        }

        return $dataProvider;
    }
}