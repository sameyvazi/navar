<?php

namespace api\components;

use Yii;

trait ControllerStatus
{
    protected function statusSuccess()
    {
        Yii::$app->getResponse()->setStatusCode(200);
    }
    
    protected function statusCreated()
    {
        Yii::$app->getResponse()->setStatusCode(201);
    }
    
    protected function statusValidation()
    {
        Yii::$app->getResponse()->setStatusCode(422);
    }
    
    protected function statusSuccessButEmpty()
    {
        Yii::$app->getResponse()->setStatusCode(204);
    }
    
    protected function statusBadRequest()
    {
        Yii::$app->getResponse()->setStatusCode(400);
    }
}
