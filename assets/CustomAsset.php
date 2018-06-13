<?php
namespace frontend\assets;
use aabc\web\AssetBundle;

class CustomAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [];
    public $js = [];
    public $depends = [
        TempAsset,
        // 'aabc\web\AabcAsset',
        // 'aabc\bootstrap\BootstrapAsset',
    ];
}
