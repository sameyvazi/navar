<?php

namespace api\modules\api\frontend\v1\models\playlist;

use common\models\mood\Mood;
use common\models\playlist\Playlist;
use Yii;
use yii\data\ActiveDataProvider;

class PlaylistSearch extends Playlist
{
    public function rules()
    {
        // only fields in rules() are searchable
        return [
            [['name', 'name_fa', 'mood_id'], 'safe']
        ];
    }

    public function search($params)
    {

        $status = \Yii::$app->helper->getTypeStatus(Yii::$app->request->headers->get('type'));

        $query = static::find()->where([$status => Playlist::STATUS_ACTIVE]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => Yii::$app->helper->getPaginatePerPage(10)],
            'sort' => ['attributes' => ['id', 'created_at', 'no']]
        ]);

        $dataProvider->sort->defaultOrder = ['no' => SORT_ASC];

        $this->load($params,'');

        $query
            ->andFilterWhere(['mood_id' => $this->mood_id])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'name_fa', $this->name_fa]);

        return $dataProvider;

    }

}