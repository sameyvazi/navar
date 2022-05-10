<?php

namespace api\modules\api\frontend\v1\models\special;

use common\models\special\Special;
use Yii;
use yii\data\ActiveDataProvider;

class SpecialSearch extends Special
{
    public function rules()
    {
        // only fields in rules() are searchable
        return [
            [['position', 'post_type'], 'integer'],
        ];
    }

    public function search($params)
    {

        $type = Yii::$app->request->headers->get('type');
        if ($type == 4){
            $type = 3;
        }

        $query = static::find()->where(['type' => $type]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => Yii::$app->helper->getPaginatePerPage(10)],
            'sort' => ['attributes' => ['id', 'created_at', 'no']]
        ]);

        $dataProvider->sort->defaultOrder = ['no' => SORT_ASC];

        $this->load($params,'');

        $query
            ->andFilterWhere(['position' => $this->position])
            ->andFilterWhere(['post_type' => $this->post_type]);

        return $dataProvider;

    }

}