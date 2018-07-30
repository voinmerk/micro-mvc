<?php

namespace app\controllers;

/**
 * Class SiteController
 * @package app\controllers
 */
class SiteController extends \app\controllers\CommonController
{
	public function actionIndex()
	{
		echo $this->view->render('site/index', $this->data);
	}
}