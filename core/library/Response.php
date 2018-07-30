<?php

namespace core\library;

/**
 * Class Response
 * @package core\library
 */
class Response
{
    /**
     * @param $link
     */
	public function redirect($link)
	{
		Header('Location: /' . $link);
		exit;
	}
}