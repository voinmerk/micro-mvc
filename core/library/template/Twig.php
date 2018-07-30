<?php

namespace core\library\template;

/**
 * Class Twig
 * @package core\library\template
 */
final class Twig {
    private $twig;
    private $loader;

    private $config;
    private $extension = '.twig';

    private $layout;
    
    public function __construct($config = array())
    {
        if(isset($config['extension'])) $this->extension = $config['extension'];

        $this->loader = new \Twig_Loader_Filesystem($config['views']);

        $this->twig = new \Twig_Environment($this->loader, array(
            'cache' => $config['cache']
        ));

        $this->layout = $config['layout'];
    }
    
    public function render($template, $data = array())
    {
        $content = isset($data['content']) ? $data['content'] : array();

        $data['content'] = $this->twig->render($template . $this->extension, $content);

        return $this->twig->render('layouts/' . $this->layout . $this->extension, $data);
    }   
}
