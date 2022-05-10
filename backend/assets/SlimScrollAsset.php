<?php

namespace backend\assets;

use yii\web\AssetBundle ;

class SlimScrollAsset extends AssetBundle
{
    public $sourcePath = '@bower/slimscroll';
    public $css = [
        
    ];
    public $js = [
        'jquery.slimscroll.min.js',
    ];
}
