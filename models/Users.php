<?php

namespace app\models;

use Core;

/**
 * Class Users
 * @package app\models
 */
class Users extends \core\Model
{
	public $info;

    /**
     * @param $id
     * @return null
     */
	public function getUser($id)
	{
		$result = $this->db->query("SELECT * FROM users WHERE id = $id LIMIT 1");

		if($result->num_rows) {
			$this->info = $result->row;

			return $result->row;
		}
		
		return NULL;
	}

    /**
     * @param $email
     * @return mixed
     */
	public function checkEmail($email)
	{
		$result = $this->db->query("SELECT * FROM users WHERE email = '$email' LIMIT 1");

		return $result->num_rows;
	}

    /**
     * @param $username
     * @return mixed
     */
	public function checkLogin($username)
	{
		$result = $this->db->query("SELECT * FROM users WHERE username = '$username' LIMIT 1");

		return $result->num_rows;
	}

    /**
     * @param array $data
     * @return mixed
     */
	public function create($data = [])
	{
        $username = $this->db->escapeString($data['username']);

        $salt = $this->generateSalt();

        $password = $this->hashPassword($data['confirm'], $salt);

        $email = $this->db->escapeString($data['email']);

		$this->db->query("INSERT INTO users (username, password, salt, email) VALUES (
			$username,
			'$password',
			'$salt',
			$email
		)");

		$id = $this->db->getLastId();

		return $id;
	}

    /**
     * @param $username
     * @param $password
     * @return bool
     */
	public function login($username, $password)
	{
		$salt = $this->getSalt($username);

		$password_hash = $this->hashPassword($password, $salt);

		$username = $this->db->escapeString($username);

		$result = $this->db->query("SELECT id, username FROM users WHERE (username = $username OR email = $username) AND password = '$password_hash'");

		if($result->num_rows) {
			return $result->row;
		}

		return false;
	}

    /**
     * @return null|string
     */
	public function getToken()
	{
		return $this->generateSalt(10);
	}

    /**
     * @param $username
     * @return bool
     */
	public function validLogin($username)
	{
		if(preg_match("/^[a-zA-Z0-9\_]*$/", $username)) {
			return true;
		}

		return false;
	}

    /**
     * @param $email
     * @return bool
     */
	public function validEmail($email)
	{
		if(preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/", $email)) {
			return true;
		}

		return false;
	}

    /**
     * @param $password
     * @return bool
     */
	public function validPassword($password)
	{
		if(preg_match('/^(?=.*\d)(?=.*[A-Za-z])[0-9A-Za-z!@#$%]{8,12}$/', $password)) {
		   return true;
		}

		return false;
	}

    /**
     * @param $password1
     * @param $password2
     * @return bool
     */
	public function confirmPassword($password1, $password2)
	{
		if($password1 == $password2) {
			return true;
		}

		return false;
	}

    /**
     * @return bool
     */
	public function logged()
	{
		if(isset($_SESSION['user'])) {
			return true;
		}

		return false;
	}

    /**
     * @param $username
     * @return null
     */
	private function getSalt($username)
	{
		$result = $this->db->query("SELECT salt FROM users WHERE username = " . $this->db->escapeString($username));

		if($result->num_rows) {
			return $result->row['salt'];
		}

		return NULL;
	}

    /**
     * @param int $length
     * @return null|string
     */
	private function generateSalt($length = 6)
	{
		$chars = 'abcdefhiknrstvwxyzETAOINSHRDLCUMWFGYPBVKXJQZ0123456789';

		$num = strlen($chars);

		$salt = null;

		for ($i = 0; $i < $length; $i++) {
			$salt .= substr($chars, rand(1, $num) - 1, 1);
		}

		return $salt;
	}

    /**
     * @param $password
     * @param $salt
     * @return string
     */
	private function hashPassword($password, $salt)
	{
		return md5(md5($password) . md5($salt));
	}
}