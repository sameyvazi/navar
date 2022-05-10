<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
    //public $basePath = '@webroot';
    //public $baseUrl = '@web';
    
    public $sourcePath = '@backend/assets/assets';
    public $css = [
        //'mpurpose/css/main.min.css',
        'waitme/waitMe.css',
        'metronic/admin/pages/css/login-rtl.css',
        'metronic/global/css/components-md-rtl.css',
        'metronic/global/css/plugins-md-rtl.css',
        'metronic/admin/layout3/css/layout-rtl.css',
        'metronic/admin/layout3/css/themes/default-rtl.css',
        'css/custom.css',
        'css/font.css',
    ];
    public $js = [
        'js/main.js',
        'waitme/waitMe.js',
        'js/jquery.transit.min.js',
        'js/modernizr-2.6.2-respond-1.1.0.min.js',
        'metronic/global/scripts/app.js',
        'metronic/admin/layout3/scripts/layout.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'backend\assets\BootstrapRtlAsset',
        'yii\bootstrap\BootstrapPluginAsset',
        'backend\assets\MouseWheelAsset',
        'backend\assets\FontAwesomeAsset',
        'backend\assets\BootstrapHoverDropdownAsset',
        'backend\assets\JqueryScrollToAsset',
        'backend\assets\SlimScrollAsset',
        'backend\assets\SmoothScrollAsset',
        'backend\assets\BlockUi',
    ];
    
}
