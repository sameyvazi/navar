<?php

namespace backend\assets;

use yii\web\AssetBundle ;

class FontAwesomeAsset extends AssetBundle
{
    public $sourcePath = '@bower/components-font-awesome';
    public $css = [
        'css/font-awesome.min.css',
    ];
    public $js = [
        
    ];
}
