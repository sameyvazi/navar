<?php

namespace backend\assets;

use yii\web\AssetBundle;

class BootstrapRtlAsset extends AssetBundle {

    public $sourcePath = '@bower/bootstrap-rtl/dist';
    public $css = ['css/bootstrap-rtl.css'];
    public $js = [];
    public $depends = ['yii\bootstrap\BootstrapAsset',];

}
