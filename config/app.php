<?php

return array(
	'db' => array(
		'adaptor' => 'MySQLi',
		'hostname' => 'localhost',
		'username' => 'root',
		'password' => '',
		'database' => 'routertest',
		'port' => 3306,
	),

	'template' => array(
		'adaptor' => 'Twig',
		'cache' => false, //DIR_CACHE,
		'views' => DIR_VIEW,
		'extension' => '.twig',
	),

	'secret_key' => 'wpBrFlF-zf5V2V9E-Zx54txwr0Idfqww',
);