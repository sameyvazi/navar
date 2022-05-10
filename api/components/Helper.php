<?php

namespace api\components;

use common\components\Helper as CommonHelper;
use Yii;

class Helper extends CommonHelper
{

    public function getPaginatePerPage($size = 20) {
        if ((int)$size > 20)
        {
            $size = 20;
        }
        return Yii::$app->getRequest()->get('per-page', $size);
    }
    
}

