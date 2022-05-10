<?php

namespace backend\models\admin;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\admin\Admin;

class AdminSearch extends Admin
{

    public function rules()
    {
        // only fields in rules() are searchable
        return [
            [['status', 'id'], 'integer'],
            [['username', 'created_at'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = static::find();
        
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => Yii::$app->helper->getPaginatePerPage(10)],
        ]);

        $dataProvider->sort->defaultOrder = ['id' => SORT_DESC];
        
        // load the seach form data and validate
        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        // adjust the query by adding the filters
        $query->andFilterWhere(['id' => $this->id])
                ->andFilterWhere(['like', 'username', $this->username])
                ->andFilterWhere(['status' => $this->status]);
            

        if (!empty($this->created_at)) {
            if ($range = Yii::$app->helper->getGridFilterRangeTimeStamp($this->created_at)) {
                $query->andFilterWhere(['between', 'created_at', $range[0], $range[1]]);
            }
        }

        return $dataProvider;
    }
}