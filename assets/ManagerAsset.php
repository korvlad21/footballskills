<?php
namespace backend\assets;
use yii\web\AssetBundle;
/**
 * Description of PropertyAsset
 *
 * @author Program INTRID
 */
class ManagerAsset extends AssetBundle{

    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
    ];
    public $js = [
        'js/manager/manager.js'
    ];
    public $depends = [
        'backend\assets\AppAsset',
    ];

}
