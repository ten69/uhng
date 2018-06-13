<?php

namespace frontend\assets;

use aabc\web\AssetBundle;


class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        // 'css/site.css',
    ];
    public $js = [
    ];
    public $depends = [
        'aabc\web\AabcAsset',
        'aabc\bootstrap\BootstrapAsset',
    ];
}
