<?php

namespace core;

use Core;

/**
 * Class Controller
 * @package core
 */
class Controller
{
	public $layout = 'main';

	public $request;

	public $view;

    /**
     * Controller constructor.
     * @throws \Exception
     */
	public function __construct()
	{
		$this->view = new \core\View(Core::$app->template, $this->layout);
		
		$this->request = new \core\library\Request();
	}
}