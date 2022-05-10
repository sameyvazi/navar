<?php

namespace common\models\activity;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\AttributeTypecastBehavior;
/**
 * This is the model class for collection "activities".
 *
 * @property \MongoDB\BSON\ObjectID $_id
 * @property string $user_id
 * @property string $ip
 * @property string $target_id
 * @property string $entity
 * @property array $data
 * @property \MongoDB\BSON\UTCDateTime $created_at
 * 
 *
 */
class Activity extends \yii\mongodb\ActiveRecord {
    
    /**
     * @return string the name of the index associated with this ActiveRecord class.
     */
    public static function collectionName()
    {
        return 'activities';
    }
    
    /**
     * @return array list of attribute names.
     */
    public function attributes()
    {
        return [
            '_id',
            'user_id',
            'ip',
            'target_id',
            'data',
            'entity',
            'created_at'
        ];
    }
    
     /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => false,
                'value' => Yii::$app->date->getMongoDate()
            ],
            'typecast' => [
                'class' => AttributeTypecastBehavior::class,
                'attributeTypes' => [
                    'user_id' => AttributeTypecastBehavior::TYPE_STRING,
                    'target_id' => AttributeTypecastBehavior::TYPE_STRING
                ],
                'typecastAfterValidate' => false,
                'typecastBeforeSave' => true,
                'typecastAfterFind' => false,
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            '_id' => Yii::t('app', 'ID'),
            'user_id' => Yii::t('app', 'Author'),
            'ip' => Yii::t('app', 'IP'),
            'target_id' => Yii::t('app', 'Target'),
            'data' => Yii::t('app', 'Data'),
            'entity' => Yii::t('app', 'Entity'),
            'created_at' => Yii::t('app', 'Created at'),
        ];
    }
    
}
