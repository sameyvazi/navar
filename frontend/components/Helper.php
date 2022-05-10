<?php

/**
 * @author S.Eyvazi <saman3yvazi@gmail.com>
 * 
 * Components for yii2
 * 
 */

namespace frontend\components;

use Yii;
class Helper extends \common\components\Helper {

    public function getPaginatePerPage($size = 20) {
        $size = Yii::$app->getRequest()->get('per-page', $size);
        
        if ($size > 50)
        {
            $size = 50;
        }
        
        return $size;
    }

}
