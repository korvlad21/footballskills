<?php
namespace backend\assets;
use yii\web\AssetBundle;
/**
 * Description of PropertyAsset
 *
 * @author Program INTRID
 */
class OrderAsset extends AssetBundle{

    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
    ];
    public $js = [
        'js/order/init.js'
    ];
    public $depends = [
        'backend\assets\AppAsset',
    ];

}
