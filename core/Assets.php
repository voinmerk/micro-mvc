<?php

namespace core;

/**
 * Class Assets
 * @package core
 */
class Assets
{
	/**
     * @var string
     *
     * @var array
     * @var array
     */
    public $basePath;

    public $css = array();
    public $js = array();

    /**
     * @param $css
     * @return null|string
     */
    public function setCss($css)
    {
        $this->css[] = $css;

        return $this->getCss();
    }

    /**
     * @return null|string
     */
    public function getCss()
    {
        $css = null;

        foreach ($this->css as $key => $value) {
            $css .= '<link rel="stylesheet" href="/web/' . $value . '">';
        }

        return $css;
    }

    /**
     * @param $js
     * @return null|string
     */
    public function setJs($js)
    {
        $this->js[] = $js;

        return $this->getJs();
    }

    /**
     * @return null|string
     */
    public function getJs()
    {
        $js = null;

        foreach ($this->js as $key => $value) {
            $js .= '<script type="text/javascript" src="/web/' . $value . '"></script>';
        }

        return $js;
    }
}