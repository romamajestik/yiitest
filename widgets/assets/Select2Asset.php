<?php
namespace app\widgets\assets;
use yii\web\AssetBundle;

/**
 * Class Select2Asset
 * @package app\widgets\assets
 */
class Select2Asset extends AssetBundle
{
    public $sourcePath = '@bower/select2/dist';

    public $css = [
        'css/select2.min.css',
    ];

    public $js = [
        'js/select2.min.js',
        'js/i18n/ru.js',
    ];

    public $depends = [
        'app\assets\AppAsset',
    ];
}