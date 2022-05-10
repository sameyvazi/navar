<?php

namespace backend\assets;

use Yii;
use yii\web\AssetBundle;

class BlockUi extends AssetBundle
{
    //public $basePath = '@webroot';
    //public $baseUrl = '@web';
    public $sourcePath = '@backend/assets/assets';
    
    public $css = [
        
    ];
    public $js = [
        'blockui/jquery.blockUI.js',
    ];
    
}
