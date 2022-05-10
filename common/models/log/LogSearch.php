<?php

namespace common\models\log;

use Yii;

/**
 * This is the model class for table "log_searches".
 *
 * @property int $id
 * @property string $query
 * @property int $status
 * @property int $created_at
 * @property int $type
 */
class LogSearch extends \yii\db\ActiveRecord
{
    const STATUS_ACTIVE = 1;
    const STATUS_DISABLED = 2;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'log_searches';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['status', 'created_at', 'type'], 'integer'],
            [['query'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'query' => Yii::t('app', 'Query'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'type' => Yii::t('app', 'Type'),
        ];
    }
}
