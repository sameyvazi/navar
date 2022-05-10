<?php

namespace api\modules\api\frontend\v1\models\artist;

use common\models\artist\Artist;
use Yii;
use yii\data\ActiveDataProvider;

class ArtistSearch extends Artist
{

    public $alphabet;

    public function rules()
    {
        // only fields in rules() are searchable
        return [
            [['activity'], 'integer'],
            [['alphabet', 'name', 'name_fa'], 'safe']
        ];
    }

    public function search($params)
    {

        $status = \Yii::$app->helper->getTypeStatus(Yii::$app->request->headers->get('type'));

        $query = static::find()->where([$status => Artist::STATUS_ACTIVE]);

        $query->select([
            'id',
            'name',
            'name_fa',
            'key',
            'key_fa',
            'activity',
            'like',
            'like_fa',
            'like_app',
            'image',
            'status',
            'status_fa',
            'status_app'
        ]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => Yii::$app->helper->getPaginatePerPage(18)],
            'sort' => ['attributes' => ['id', 'created_at', 'like', 'like_fa', 'like_app']]
            //'sort' => false,
        ]);

        $like = \Yii::$app->helper->getTypeLike(Yii::$app->request->headers->get('type'));

        $dataProvider->sort->defaultOrder = [$like => SORT_DESC];

        $this->load($params,'');

        $query
            ->andFilterWhere(['activity' => $this->activity != null ? (int)$this->activity : null])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'name_fa', $this->name_fa]);

        if ($this->alphabet == 'other'){
            $query->andFilterWhere(['OR', ['REGEXP', 'name', '^[0-9]+'], ['REGEXP', 'name_fa', '^[0-9]+']]);
        }else{
            $query->andFilterWhere(['OR', ['like', 'name', $this->alphabet.'%', false], ['like', 'name_fa', $this->alphabet.'%', false]]);
        }

        return $dataProvider;


    }

}