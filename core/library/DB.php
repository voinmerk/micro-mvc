<?php

namespace core\library;

/**
 * Class DB
 * @package mvc\library
 */
class DB {
	private $adaptor;

    /**
     * DB constructor.
     * @param $db
     * @throws \Exception
     */
	public function __construct($db) {
		$class = 'core\\library\\db\\' . $db['adaptor'];

		if (class_exists($class)) {
			$this->adaptor = new $class($db['hostname'], $db['username'], $db['password'], $db['database'], $db['port']);
		} else {
			throw new \Exception('Error: Could not load database adaptor ' . $db['adaptor'] . '!');
		}
	}

    /**
     * @param $sql
     * @return mixed
     */
	public function query($sql) {
		return $this->adaptor->query($sql);
	}

    /**
     * @param $value
     * @return mixed
     */
	public function escape($value) {
		return $this->adaptor->escape($value);
	}

    /**
     * @param $value
     * @return string
     */
	public function escapeString($value) {
		return "'" . $this->adaptor->escape($value) . "'";
	}

    /**
     * @return mixed
     */
	public function countAffected() {
		return $this->adaptor->countAffected();
	}

    /**
     * @return mixed
     */
	public function getLastId() {
		return $this->adaptor->getLastId();
	}

    /**
     * @return mixed
     */
	public function connected() {
		return $this->adaptor->connected();
	}
}