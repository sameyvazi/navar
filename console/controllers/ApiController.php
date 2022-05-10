<?php

namespace console\controllers;

use yii\console\Controller;
use Yii;
use yii\helpers\Console;

class ApiController extends Controller {
    
    public function actionIndex()
    {
        /*$command = 'apidoc -i ' . Yii::getAlias('@api/modules/api/backend/v1');
        $command .= ' -o ' . Yii::getAlias('@api/web/backend/v1');
        
        exec($command);*/
        
        $command = 'apidoc -i ' . Yii::getAlias('@api/modules/api/frontend/v1');
        $command .= ' -o ' . Yii::getAlias('@api/web/frontend/v1');
        
        exec($command);
        
        $this->stdout("API document successfully created.\n", Console::FG_GREEN);
        
    }

}