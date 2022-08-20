<?php
namespace backend\assets;
use yii\web\AssetBundle;
/**
 * Description of PropertyAsset
 *
 * @author Program INTRID
 */
class CityAsset extends AssetBundle{

    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
    ];
    public $js = [
        'https://api-maps.yandex.ru/2.1/?lang=ru-RU&amp;load=package.full',
        'js/city/city.js'
    ];
    public $depends = [
        'backend\assets\AppAsset',
    ];

}
