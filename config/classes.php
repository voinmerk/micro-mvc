<?php

return array(
	// assets
	'app\\assets\\AppAssets' => DIR_ASSET. '/AppAssets.php',

	// controllers
	'app\\controllers\\AccountController' => DIR_CONTROLLER . '/AccountController.php',
	'app\\controllers\\CommonController' => DIR_CONTROLLER . '/CommonController.php',
	'app\\controllers\\SiteController' => DIR_CONTROLLER . '/SiteController.php',

	// models
	'app\\models\\Users' => DIR_MODEL . '/Users.php',
	'app\\models\\UsersImage' => DIR_MODEL . '/UsersImage.php',

	// core
	'core\\Application' => DIR_CORE . '/Application.php',
	'core\\Assets' => DIR_CORE . '/Assets.php',
	'core\\BaseCore' => DIR_CORE . '/BaseCore.php',
	'core\\Config' => DIR_CORE . '/Config.php',
	'core\\Controller' => DIR_CORE . '/Controller.php',
	'core\\Model' => DIR_CORE . '/Model.php',
	'core\\Router' => DIR_CORE . '/Router.php',
	'core\\View' => DIR_CORE . '/View.php',

	// core/library
	'core\\library\\DB' => DIR_CORE . '/library/DB.php',
	'core\\library\\Image' => DIR_CORE . '/library/Image.php',
	'core\\library\\Request' => DIR_CORE . '/library/Request.php',
	'core\\library\\Response' => DIR_CORE . '/library/Response.php',
	'core\\library\\Session' => DIR_CORE . '/library/Session.php',
	'core\\library\\Template' => DIR_CORE . '/library/Template.php',

	// core/library/db
	'core\\library\\db\\mPDO' => DIR_CORE . '/library/db/mPDO.php',
	'core\\library\\db\\MSSQL' => DIR_CORE . '/library/db/MSSQL.php',
	'core\\library\\db\\MySQL' => DIR_CORE . '/library/db/MySQL.php',
	'core\\library\\db\\MySQLi' => DIR_CORE . '/library/db/MySQLi.php',
	'core\\library\\db\\Postgre' => DIR_CORE . '/library/db/Postgre.php',

	// core/liabrary/session
	'core\\library\\session\\DB' => DIR_CORE . '/library/session/DB.php',
	'core\\library\\session\\Files' => DIR_CORE . '/library/session/Files.php',

	// core/library/template
	'core\\library\\template\\Twig' => DIR_CORE . '/library/template/Twig.php',
);