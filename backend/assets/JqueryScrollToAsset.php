<?php

namespace backend\assets;

use yii\web\AssetBundle ;

class JqueryScrollToAsset extends AssetBundle
{
    public $sourcePath = '@bower/jquery.scrollTo';
    public $css = [
        
    ];
    public $js = [
        'jquery.scrollTo.min.js',
    ];
}
