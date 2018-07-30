<?php

namespace core;

use Core;

/**
 * Class Router
 * @package core
 */
class Router
{
	private $routes = array();
	private $params = array();

	private $request;

    /**
     * Router constructor.
     */
	public function __construct()
	{
		$routes = require DIR_CONFIG . '/routes.php';

 		$this->request = new \core\library\Request();

 		foreach($routes as $route => $params) {
 			$this->add($route, $params);
 		}
	}

    /**
     * @param $route
     * @param array $params
     */
	public function add($route, $params = array())
	{
		$route = '#^' . $route . '$#';

 		$this->routes[$route] = $params;
	}

    /**
     * @return bool
     */
	public function match()
	{
		$uri = trim($this->request->server['REQUEST_URI'], '/');

 		foreach ($this->routes as $route => $params) {
 			if(preg_match($route, $uri, $matches)) {
 				$this->params = $params;

 				return true;
 			}
 		}

 		return false;
	}

    /**
     * @throws \Exception
     */
	public function execute()
	{
		if($this->match()) {
 			$controller = 'app\\controllers\\' . ucfirst($this->params['controller']) . 'Controller';

 			if(class_exists($controller)) {
 				$action = 'action' . ucfirst($this->params['action']);

 				if(method_exists($controller, $action)) {
 					Core::$app->controller = $this->params['controller'];
 					Core::$app->action = $this->params['action'];

 					Core::$route = 200;

 					$controller = new $controller();

 					$controller->$action();
 				} else {
 					Core::$route = 404;

 					throw new \Exception("Method '$action' in class '$controller' not found");
 				}
 			} else {
 				Core::$route = 404;

 				throw new \Exception("Class '$controller' not found");
 			}
 		} else {
 			Core::$route = 404;
 			
 			$controller = 'app\\controllers\\SiteController';

 			$controller = new $controller;
 			$action = 'actionIndex';

 			$controller->$action();
 		}
	}
}