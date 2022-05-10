<?php

namespace backend\assets;

use yii\web\AssetBundle ;

class FancyboxAsset extends AssetBundle
{
    public $sourcePath = '@bower/fancybox/src';
    //public $sourcePath = '@bower/fancybox/sorce';
    public $css = [
        //'jquery.fancybox.css',
    ];
    public $js = [
        //'jquery.fancybox.pack.js',
    ];
}
