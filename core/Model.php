<?php

namespace core;

use Core;

/**
 * Class Model
 * @package core
 */
class Model
{
	public $db;

    /**
     * Model constructor.
     * @throws \Exception
     */
	public function __construct()
	{
		$this->db = new \core\library\DB(Core::$app->db);
	}
}