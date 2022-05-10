<?php

use common\models\user\User;

class m161107_123327_create_collection_users extends \yii\mongodb\Migration
{
    public function up()
    {
        $this->createCollection(User::collectionName());
        
        $this->createIndex(User::collectionName(), 'mobile', [
            'unique' => true,
            'partialFilterExpression' => [
                'mobile' => ['$exists' => true]
            ],
        ]);
        
        $this->createIndex(User::collectionName(), 'email', [
            'unique' => true,
            'partialFilterExpression' => [
                'email' => ['$exists' => true]
            ],
        ]);
        
        $this->createIndex(User::collectionName(), 'activation_code', [
            'partialFilterExpression' => [
                'activation_code' => ['$exists' => true]
            ],
        ]);
        
        $this->createIndex(User::collectionName(), 'status');
        
        $this->createIndex(User::collectionName(), ['created_at' => -1]);
        
        return true;
    }

    public function down()
    {
        $this->dropCollection(User::collectionName());

        return true;
    }
}
