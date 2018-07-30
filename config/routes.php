<?php

return array(
	// Site controller
	'' => array(
		'controller' => 'site',
		'action' => 'index',
	),

	'index' => array(
		'controller' => 'site',
		'action' => 'index',
	),

	// Account controller
	'login' => array(
		'controller' => 'account',
		'action' => 'login',
	),

	'signup' => array(
		'controller' => 'account',
		'action' => 'signup',
	),

	'signup/success' => array(
		'controller' => 'account',
		'action' => 'SignupSuccess',
	),

	'user' => array(
		'controller' => 'account',
		'action' => 'index',
	),

	'user/images' => array(
		'controller' => 'account',
		'action' => 'images',
	),

	'logout' => array(
		'controller' => 'account',
		'action' => 'logout',
	),
);