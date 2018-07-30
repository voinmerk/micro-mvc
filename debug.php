<?php

/**
 * Включим вывод ошибок на экран
 */
ini_set('display_errors', true);
error_reporting(E_ALL);

/**
 * @param $object
 */
function debug($object) {
	echo '<pre>' . var_dump($object) . '</pre>';
}