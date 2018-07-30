<?php

namespace core;

use Core;

/**
 * Class View
 */
class View
{
	public $error = 'site/error';

	private $adaptor;

    /**
     * View constructor.
     * @param $config
     * @throws \Exception
     */
    public function __construct($config, $layout) {
        $class = 'core\\library\\template\\' . $config['adaptor'];

        if (class_exists($class)) {
            $this->adaptor = new $class(
            	array(
            		'cache' => $config['cache'],
            		'views' => $config['views'], 
            		'layout' => $layout
            	)
            );
        } else {
            throw new \Exception('Error: Could not load template adaptor ' . $config['adaptor'] . '!');
        }
    }

    /**
     * @param $view
     * @param array $data
     * @return mixed
     */
	public function render($template, $data = array())
    {
        if(Core::$route != 200) {
            $data['content']['error'] = array(
                'code' => Core::$route,
                'message' => 'Page not found!',
            );

            return $this->adaptor->render($this->error, $data);
        }

        return $this->adaptor->render($template, $data);
    }
}

