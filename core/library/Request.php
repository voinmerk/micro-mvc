<?php

namespace core\library;

/**
 * Class Request
 * @package core\library
 */
class Request
{
	public $get = array();
	public $post = array();
	public $cookie = array();
	public $files = array();
	public $server = array();

    /**
     * Request constructor.
     */
	public function __construct()
	{
		$this->get = $this->clean($_GET);
		$this->post = $this->clean($_POST);
		$this->request = $this->clean($_REQUEST);
		$this->cookie = $this->clean($_COOKIE);
		$this->files = $this->clean($_FILES);
		$this->server = $this->clean($_SERVER);
	}

    /**
     * @return bool
     */
	public function IsGet()
	{
		if($this->server['REQUEST_METHOD'] == 'GET') {
			return true;
		}

		return false;
	}

    /**
     * @return bool
     */
	public function IsHead()
	{
		if($this->server['REQUEST_METHOD'] == 'HEAD') {
			return true;
		}

		return false;
	}

    /**
     * @return bool
     */
	public function IsPost()
	{
		if($this->server['REQUEST_METHOD'] == 'POST') {
			return true;
		}

		return false;
	}

    /**
     * @return bool
     */
	public function IsPut()
	{
		if($this->server['REQUEST_METHOD'] == 'PUT') {
			return true;
		}

		return false;
	}

    /**
     * @param $data
     * @return array|string
     */
	public function clean($data) {
		if (is_array($data)) {
			foreach ($data as $key => $value) {
				unset($data[$key]);

				$data[$this->clean($key)] = $this->clean($value);
			}
		} else {
			$data = htmlspecialchars($data, ENT_COMPAT, 'UTF-8');
		}

		return $data;
	}
}