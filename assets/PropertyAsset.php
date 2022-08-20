<?php
namespace backend\assets;
use yii\web\AssetBundle;
/**
 * Description of PropertyAsset
 *
 * @author Program INTRID
 */
class PropertyAsset extends AssetBundle{

    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
    ];
    public $js = [
        'js/property/property.js'
    ];
    public $depends = [
        'backend\assets\AppAsset',
    ];

}
