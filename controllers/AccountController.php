<?php

namespace app\controllers;

use Core;

/**
 * Class AccountController
 * @package app\controllers
 */
class AccountController extends \app\controllers\CommonController
{
	public $user;
	public $request;
	public $assets;

	public $view;

	public function actionIndex()
	{
		if(!$this->user->logged()) {
			return $this->response->redirect('login');
		}
		
		$this->data['title'] = 'Аккаунт';
		
		echo $this->view->render('account/index', $this->data);
	}

	public function actionImages()
	{
		if(!$this->user->logged()) {
			return $this->response->redirect('login');
		}

		$request = new \core\library\Request();

		$images = new \app\models\UsersImage();

		$this->data['content']['file_size'] = ini_get('upload_max_filesize');

		$this->data['title'] = 'Ваши изображения';

		$this->data['content']['images'] = $images->list($this->user->info['id']);

		if($request->IsPost()) {
			$post = $request->post;

			$imageClass = new \core\library\Image();

			$path = $request->files['file']['name'];
			$ext = pathinfo($path, PATHINFO_EXTENSION);

			$fileName = basename($request->files['file']['name']);

			$fileNameHash = md5($fileName . $this->user->getToken());

			$imageFile = DIR_FILES . '/images/original/' . $fileNameHash . '.' . $ext;

			if (move_uploaded_file($request->files['file']['tmp_name'], $imageFile)) {
				$imageClass->thumb($imageFile, DIR_FILES . '/images/100x100/' . $fileNameHash . '.' . $ext, 100, 100, USE_HOWSET);

				$sqlFile = '/web/files/images/100x100/' . $fileNameHash . '.' . $ext;

				$data = array(
					'user_id' => $this->user->info['id'],
					'title' => $post['title'],
					'description' => $post['description'],
					'src' => $sqlFile,
				);

			    $result = $images->create($data);

			    if($result) {
			    	$_SESSION['alertInfo'] = 'Изображение успешно добавлено!';

			    	$this->response->redirect('user/images');
			    } else {
			    	$this->data['content']['alert'] = array(
				    	'message' => 'Error create file database!',
				    	'class' => 'alert-danger',
				    );
			    }
			} else {
			    $this->data['content']['alert'] = array(
			    	'message' => 'Error file move! $imageFile: ' . $imageFile . '<br> Tmp: ' . $request->files['file']['tmp_name'] . '<br> Error: ' . $request->files['file']['error'],
			    	'class' => 'alert-danger',
			    );
			}
		}

		if(isset($_SESSION['alertInfo'])) {
			$data['content']['alert'] = array(
				'message' => $_SESSION['alertInfo'],
				'class' => 'alert-success',
			);

			unset($_SESSION['alertInfo']);
		}

		echo $this->view->render('account/images', $this->data);
	}

	public function actionSignup()
	{
		if($this->user->logged()) {
			return $this->response->redirect('user');
		}

		$this->data['title'] = 'Регистрация';

		$request = new \core\library\Request();

		// Если POST запрос
		if($request->IsPost()) {
			$validate = true;

			$form = array();

			$post = $request->post;

			if($this->user->checkLogin($post['username'])) {
				$validate = false;

				$form['username'] = array(
					'text' => $post['username'],
					'validClass' => 'is-invalid',
					'validate' => false,
					'message' => 'Введённое вами имя пользователя уже занято!',
				);
			} else {
				$form['username'] = array(
					'text' => $post['username'],
					'validClass' => 'is-valid',
					'validate' => true,
					'message' => '',
				);
			}

			if(!$this->user->validLogin($post['username'])) {
				$validate = false;

				$form['username'] = array(
					'text' => $post['username'],
					'validClass' => 'is-invalid',
					'validate' => false,
					'message' => 'Некорректнный логин! Используйте символы от A до Z верхнего и нежнего регистра.',
				);
			} else {
				$form['username'] = array(
					'text' => $post['username'],
					'validClass' => 'is-valid',
					'validate' => true,
					'message' => '',
				);
			}

			if($this->user->checkEmail($post['email'])) {
				$validate = false;

				$form['email'] = array(
					'text' => $post['email'],
					'validClass' => 'is-invalid',
					'validate' => false,
					'message' => 'Введённый эл. адрес уже зарегистрирован!',
				);
			} else {
				$form['email'] = array(
					'text' => $post['email'],
					'validClass' => 'is-valid',
					'validate' => true,
					'message' => '',
				);
			}

			if(!$this->user->validEmail($post['email'])) {
				$validate = false;

				$form['email'] = array(
					'text' => $post['email'],
					'validClass' => 'is-invalid',
					'validate' => false,
					'message' => 'Некорректнный эл. адрес! Пример: example@example.com',
				);
			} else {
				$form['email'] = array(
					'text' => $post['email'],
					'validClass' => 'is-valid',
					'validate' => true,
					'message' => '',
				);
			}

			if(!$this->user->validPassword($post['password'])) {
				$validate = false;

				$form['password'] = array(
					'text' => $post['password'],
					'validClass' => 'is-invalid',
					'validate' => false,
					'message' => 'Некорректнный пароль!',
				);
			} else {
				$form['password'] = array(
					'text' => $post['password'],
					'validClass' => 'is-valid',
					'validate' => true,
					'message' => '',
				);
			}

			if(!$this->user->confirmPassword($post['password'], $post['confirm'])) {
				$validate = false;

				$form['confirm'] = array(
					'text' => $post['confirm'],
					'validClass' => 'is-invalid',
					'validate' => false,
					'message' => 'Пароли не совпадают!',
				);
			} else {
				$form['confirm'] = array(
					'text' => $post['confirm'],
					'validClass' => 'is-valid',
					'validate' => true,
					'message' => '',
				);
			}

			if($validate) {
				$result = $this->user->create($post);

				if(!$result) {
					$form['alert'] = array(
						'message' => 'Неизвестная ошибка. Попробуйте позже ;(',
						'class' => 'alert-danger',
					);
				} else {
					$this->response->redirect('signup/success');
				}
			}

			$this->data['content']['form'] = $form;
		}

		echo $this->view->render('account/signup', $this->data);
	}

	public function actionSignupSuccess()
	{
		echo $this->view->render('account/signup-success', $this->data);
	}

	public function actionLogin()
	{
		if($this->user->logged()) {
			return $this->response->redirect('user');
		}

		$this->data['title'] = 'Вход';

		$request = new \core\library\Request();

		// Если POST запрос
		if($request->IsPost()) {
			$post = $request->post;

			$result = $this->user->login($post['username'], $post['password']);

			if($result == false) {
				$this->data['content']['form'] = $post;

				$this->data['content']['form']['alert'] = array(
					'message' => 'Неверный логин или пароль!',
					'class' => 'alert-danger',
				);
			} else {

				$_SESSION['user']['user_id'] = $result['id'];
				$_SESSION['user']['username'] = $result['username'];
				$_SESSION['user']['token'] = $this->user->getToken();
				$_SESSION['user']['time'] = time() + 900;

				if(isset($post['remember'])) {
					$_SESSION['user']['remember'] = 1;

					$_SESSION['user']['time'] = time() + (60 * 60 * 24 * 30); // + месяц
				} else {
					$_SESSION['user']['remember'] = 0;
				}

				$_SESSION['user']['auth_hash'] = md5(md5($_SESSION['user']['user_id']) . '_' . md5($_SESSION['user']['token']) . '_' . md5($_SESSION['user']['username']) . '_' . md5(Core::$app->secret_key));

				$this->response->redirect('user');
			}
		}

		echo $this->view->render('account/login', $this->data);
	}

	public function actionLogout()
	{
		unset($_SESSION['user']);

		return $this->response->redirect('index');
	}
}