<?php

use common\models\activity\Activity;

class m161103_144357_create_collection_activities extends \yii\mongodb\Migration
{
    public function up()
    {
        $this->createCollection(Activity::collectionName());
        
        $this->createIndex(Activity::collectionName(), 'user_id', [
            'partialFilterExpression' => [
                'user_id' => ['$exists' => true]
            ],
        ]);
        
        $this->createIndex(Activity::collectionName(), 'target_id');
        
        
        return true;
        
    }

    public function down()
    {
        $this->dropCollection(Activity::collectionName());

        return true;
    }
}
