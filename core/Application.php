<?php

namespace core;

use Core;

/**
 * Class Application
 * @package core
 */
class Application
{
	public $router;

    /**
     * Application constructor.
     * @param $config
     */
	public function __construct($config)
	{
		Core::$app = new \core\Config();

		Core::$app->secret_key = $config['secret_key'];
		Core::$app->template = $config['template'];
		Core::$app->db = $config['db'];
	}

    /**
     * @throws \Exception
     */
	public function start()
	{
		@session_start();
		
		$this->router = new \core\Router();

		$this->router->execute();
	}
}