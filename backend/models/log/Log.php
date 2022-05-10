<?php

namespace backend\models\log;

use Yii;

/**
 * This is the model class for collection "log".
 *
 * @property string $_id
 * @property integer $level
 * @property string $category
 * @property double $log_time
 * @property string $prefix
 * @property string $message
 *
 */
class Log extends \yii\mongodb\ActiveRecord {

    /**
     * @return string the name of the index associated with this ActiveRecord class.
     */
    public static function collectionName()
    {
        return 'log';
    }
    
    /**
     * @return array list of attribute names.
     */
    public function attributes()
    {
        return [
            '_id',
            'level',
            'category',
            'log_time',
            'prefix',
            'message',
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            '_id' => Yii::t('app', 'ID'),
            'level' => Yii::t('app', 'Level'),
            'category' => Yii::t('app', 'Category'),
            'log_time' => Yii::t('app', 'Log Time'),
            'prefix' => Yii::t('app', 'Prefix'),
            'message' => Yii::t('app', 'Message'),
        ];
    }
    
}
