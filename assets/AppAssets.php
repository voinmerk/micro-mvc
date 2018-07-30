<?php

namespace app\assets;

/**
 * Class AppAssets
 * @package app\assets
 */
class AppAssets extends \core\Assets
{
    /**
     * @var array
     */
	public $js = array(
        'js/jquery.js',
        'js/bootstrap.min.js',
        'js/all.min.js',
        'js/index.js',
    );

    /**
     * @var array
     */
	public $css = array(
        'css/bootstrap.min.css',
        'css/all.min.css',
        'css/index.css',
    );
}