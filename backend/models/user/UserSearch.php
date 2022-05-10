<?php

namespace backend\models\user;

use Yii;
use yii\data\ActiveDataProvider;
use common\models\user\User;

class UserSearch extends User
{

    public function rules()
    {
        // only fields in rules() are searchable
        return [
            [['status'], 'integer'],
            [['id', 'created_at', 'mobile'], 'safe'],
        ];
    }

    public function search($params)
    {
        $query = static::find();

        $query->select([
            'id',
            'mobile',
            'username',
            'email',
            'status',
            'avatar',
            'created_at'
        ]);

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
            ->andFilterWhere(['mobile' => $this->mobile])
            ->andFilterWhere(['email' => $this->email])
            ->andFilterWhere(['status' => $this->status != null ? (int)$this->status : null]);


        if (!empty($this->created_at)) {
            if ($range = Yii::$app->helper->getGridFilterRangeTimeStamp($this->created_at)) {
                $query->andFilterWhere(['between', 'created_at', Yii::$app->date->getMongoDate($range[0]), Yii::$app->date->getMongoDate($range[1])]);
            }
        }

        return $dataProvider;
    }
}