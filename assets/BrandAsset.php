<?php
namespace backend\assets;
use yii\web\AssetBundle;
/**
 * Description of PropertyAsset
 *
 * @author Program INTRID
 */
class BrandAsset extends AssetBundle{

    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
    ];
    public $js = [
        'js/brand/brand.js'
    ];
    public $depends = [
        'backend\assets\AppAsset',
    ];

}
