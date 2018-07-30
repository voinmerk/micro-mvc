<?php

namespace core\library\db;

/**
 * Class Postgre
 * @package core\library\db
 */
final class Postgre {
	private $link;

    /**
     * Postgre constructor.
     * @param $hostname
     * @param $username
     * @param $password
     * @param $database
     * @param string $port
     * @throws \Exception
     */
	public function __construct($hostname, $username, $password, $database, $port = '5432') {
		if (!$this->link = pg_connect('hostname=' . $hostname . ' port=' . $port .  ' username=' . $username . ' password='	. $password . ' database=' . $database)) {
			throw new \Exception('Error: Could not make a database link using ' . $username . '@' . $hostname);
		}

		if (!mysql_select_db($database, $this->link)) {
			throw new \Exception('Error: Could not connect to database ' . $database);
		}

		pg_query($this->link, "SET CLIENT_ENCODING TO 'UTF8'");
	}

    /**
     * @param $sql
     * @return bool|\stdClass
     * @throws \Exception
     */
	public function query($sql) {
		$resource = pg_query($this->link, $sql);

		if ($resource) {
			if (is_resource($resource)) {
				$i = 0;

				$data = array();

				while ($result = pg_fetch_assoc($resource)) {
					$data[$i] = $result;

					$i++;
				}

				pg_free_result($resource);

				$query = new \stdClass();
				$query->row = isset($data[0]) ? $data[0] : array();
				$query->rows = $data;
				$query->num_rows = $i;

				unset($data);

				return $query;
			} else {
				return true;
			}
		} else {
			throw new \Exception('Error: ' . pg_result_error($this->link) . '<br />' . $sql);
		}
	}

    /**
     * @param $value
     * @return string
     */
	public function escape($value) {
		return pg_escape_string($this->link, $value);
	}

    /**
     * @return int
     */
	public function countAffected() {
		return pg_affected_rows($this->link);
	}

    /**
     * @return mixed
     * @throws \Exception
     */
	public function getLastId() {
		$query = $this->query("SELECT LASTVAL() AS `id`");

		return $query->row['id'];
	}

	public function __destruct() {
		pg_close($this->link);
	}
}