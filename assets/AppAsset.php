<?php

namespace app\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/fonts/font-awesome.min.css',
        'css/AdminLTE.css',
        'css/lightcase.css',
        'css/lightcase-no-breakpoint.css',
        'css/style.css',
        'css/stylePr.css'
    ];
    public $js = [
        'js/jquery.hotkeys.js',
        'js/pubsub.min.js',
//        'https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.min.js',
        'js/adminlte.js',
        'js/lightcase.js',
        'js/script.js',
        'js/myscript.js', //
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapPluginAsset',
    ];
}
