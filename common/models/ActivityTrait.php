<?php
namespace common\models;

use app\models\activity\Activity;
use Yii;

trait ActivityTrait
{
    
    protected function attachActivity()
    {

        $this->createSaveActivityEvent();
    }
    
    protected function createSaveActivityEvent()
    {
        $this->on(parent::EVENT_AFTER_INSERT, [$this, 'saveInsertActivity']);
        $this->on(parent::EVENT_AFTER_UPDATE, [$this, 'saveUpdateActivity']);
        $this->on(parent::EVENT_AFTER_DELETE, [$this, 'deleteActivity']);
    }
    
    protected function saveInsertActivity($event)
    {
        $this->saveActivity($this->getAttributes());
    }
    
    protected function saveUpdateActivity($changedAttributes)
    {
        $data = $changedAttributes->changedAttributes;
        $attributes = $this->getAttributes();
        foreach($data as $key => $value)
        {
            if (array_key_exists($key, $attributes))
            {
                $data[$key] = $attributes[$key];
            }
        }
        $this->saveActivity($data);
    }
    
    protected function deleteActivity()
    {
        $this->saveActivity(['deleted' => 1]);
    }
    
    protected function saveActivity($data)
    {
        foreach($this->guardedForActivity as $value)
        {
            if (isset($data[$value]))
            {
                unset($data[$value]);
            }
        }
        
        $activity = new Activity();
        
        if (!Yii::$app->request->isConsoleRequest)
        {
            $activity->ip = Yii::$app->getRequest()->getUserIP();
            $activity->user_id = Yii::$app->getUser()->id;
        }
        
        $activity->entity = (new \ReflectionClass($this))->getShortName();
        $activity->target_id = $this->getPrimaryKey();
        $activity->data = $data;
        
        return $activity->save();
    }
}
