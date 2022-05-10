<?php

namespace api\modules\api\frontend\v1\models\follow;

use common\models\follower\Follower;
use Yii;
use yii\data\ActiveDataProvider;

class FollowSearch extends Follower
{
    public $date_range;

    public function rules()
    {
        // only fields in rules() are searchable
        return [
            [['post_type'], 'integer'],
        ];
    }

    public function search($params)
    {
        $query = static::find()->where(['user_id' => Yii::$app->user->id]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => Yii::$app->helper->getPaginatePerPage(10)],
            'sort' => ['attributes' => ['id', 'created_at']]
        ]);

        $dataProvider->sort->defaultOrder = ['created_at' => SORT_DESC];

        $this->load($params,'');

        $query->andFilterWhere(['post_type' => $this->post_type]);


        return $dataProvider;

    }

}