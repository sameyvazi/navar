<?php

namespace api\modules\api\frontend\v1\models\mood;

use common\models\mood\Mood;
use Yii;
use yii\data\ActiveDataProvider;

class MoodSearch extends Mood
{
    public function rules()
    {
        // only fields in rules() are searchable
        return [
            [['name', 'name_fa'], 'safe']
        ];
    }

    public function search($params)
    {

        $status = \Yii::$app->helper->getTypeStatus(Yii::$app->request->headers->get('type'));

        $query = static::find()->where([$status => Mood::STATUS_ACTIVE])->andWhere(['<>', 'id', Yii::$app->params['moodIdUsers']]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => Yii::$app->helper->getPaginatePerPage(10)],
            'sort' => ['attributes' => ['id', 'created_at', 'no']]
        ]);

        $dataProvider->sort->defaultOrder = ['no' => SORT_ASC];

        $this->load($params,'');

        $query
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'name_fa', $this->name_fa]);

        return $dataProvider;

    }

}