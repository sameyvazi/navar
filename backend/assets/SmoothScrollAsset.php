<?php

namespace backend\assets;

use yii\web\AssetBundle ;

class SmoothScrollAsset extends AssetBundle
{
    public $sourcePath = '@bower/smooth-scroll';
    public $css = [
        
    ];
    public $js = [
        'smooth-scroll.js',
    ];
}
