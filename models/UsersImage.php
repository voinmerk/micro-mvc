<?php

namespace app\models;

/**
 * Class UsersImage
 * @package app\models
 */
class UsersImage extends \core\Model
{
    /**
     * @param array $data
     * @return mixed
     */
	public function create($data = array())
	{
		$user_id = (int) $data['user_id'];
		$title = $this->db->escapeString($data['title']);
		$description = $this->db->escapeString($data['description']);
		$src = $this->db->escapeString($data['src']);

		$this->db->query("INSERT INTO users_image (user_id, title, description, src) VALUES ($user_id, $title, $description, $src)");

		$id = $this->db->getLastId();

		return $id;
	}

    /**
     * @param $user_id
     * @return array
     */
	public function list($user_id)
	{
		$images = $this->db->query("SELECT * FROM users_image WHERE user_id = $user_id ORDER BY date_create DESC");

		$data = array();

		if($images->num_rows) {
			$data['count'] = $images->num_rows;

			foreach($images->rows as $image) {
				$data['data'][] = array(
					'id' => $image['id'],
					'title' => $image['title'],
					'description' => $image['description'],
					'src' => $image['src'],
				);
			}
		} else {
			$data['count'] = 0;
		}

		return $data;
	}

    /**
     * @param $id
     */
	public function delete($id)
	{
		$this->db->query("DELETE FROM users_image WHERE id = $id");
	}
}