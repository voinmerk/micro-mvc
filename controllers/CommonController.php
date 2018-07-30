<?php

namespace app\controllers;

use Core;

/**
 * Class CommonController
 * @package app\controllers
 */
class CommonController extends \core\Controller
{
	protected $user;
	//protected $request;
	protected $assets;

	protected $response;

	public $data = array();

	public $view;

    /**
     * CommonController constructor.
     * @throws \Exception
     */
	function __construct()
	{
		$this->view = new \core\View(Core::$app->template, $this->layout);

		$this->user = new \app\models\Users();

		if($this->user->logged()) {
			if(isset($_SESSION['user'])) {
				$auth_hash = md5(md5($_SESSION['user']['user_id']) . '_' . md5($_SESSION['user']['token']) . '_' . md5($_SESSION['user']['username']) . '_' . md5(Core::$app->secret_key));

				if($_SESSION['user']['auth_hash'] != $auth_hash) {
					unset($_SESSION['user']);
				} else {
					if(!$_SESSION['user']['remember']) {
						if($_SESSION['user']['time'] < time()) {
							unset($_SESSION['user']);
						}
					}
				}

				$this->user->getUser($_SESSION['user']['user_id']);

				$this->data['user'] = $this->user->info;
				$this->data['user']['logged'] = true;

				$this->data['content']['user'] = $this->data['user'];
			}
		}
		
		$this->response = new \core\library\Response();

		$this->assets = new \app\assets\AppAssets();

		$this->data['head'] = array(
			'css' => $this->assets->getCss(),
			'js' => $this->assets->getJs(),
		);

		$this->data['user']['logged'] = $this->user->logged();

		$this->data[Core::$app->controller][Core::$app->action]['activeClass'] = 'active';

		/*$data['breadcrumps'][] = array(
			'url' => 'index',
			'name'=> 'Главная',
			'active' => '',
		);*/
	}
}