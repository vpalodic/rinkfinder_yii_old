<?php

class SiteController extends Controller
{
	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha' => array('class' => 'CCaptchaAction',
							   'backColor' => 0xFFFFFF,
							   ),
			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName
			'page' => array('class' => 'CViewAction',
							),
			);
	}

	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{
		// renders the view file 'protected/views/site/index.php'
		// using the default layout 'protected/views/layouts/main.php'
		$this->render('index');
	}

	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
		if($error=Yii::app()->errorHandler->error)
		{
			if(Yii::app()->request->isAjaxRequest)
				echo $error['message'];
			else
				$this->render('error', $error);
		}
	}

	/**
	 * Displays the contact page
	 */
	public function actionContact()
	{
		$model = new ContactForm;

		// ajax validator
		if(isset($_POST['ajax']) && $_POST['ajax'] === 'contact-form') {
			echo CActiveForm::validate(array($model));
			Yii::app()->end();
		}

		if(isset($_POST['ContactForm']))
		{
			$model->attributes = $_POST['ContactForm'];

			if($model->validate())
			{
				$mailSent = Yii::app()->sendMail(array('name' => CHtml::encode($model->name),
													   'email' => CHtml::encode($model->email)),
												'vj.palodichuk@gmail.com',
												CHtml::encode($model->subject),
												CHtml::encode($model->body));
				if($mailSent === true) {
					Yii::app()->user->setFlash(TbHtml::ALERT_COLOR_SUCCESS,
											   'Thank you for contacting us. We will respond to you as soon as possible.');
				} else {
					Yii::app()->user->setFlash(TbHtml::ALERT_COLOR_ERROR,
											   'Thank you for trying to contact us. An error occurred, please try again later.<br><br>Error: ' . $mailSent);

				}
				$this->refresh();
			}
		}

		// Preload the form if the user is logged in!
		if(!Yii::app()->user->isGuest) {
			$user = User::model()->findByPk(Yii::app()->user->id);

			$model->name = $user->profile->first_name . ' ' . $user->profile->last_name;
			$model->email = $user->email;
		}

		$this->render('contact', array('model' => $model));
	}

	/**
	 * Displays the login page
	 */
	public function actionLogin()
	{
		if(Yii::app()->user->isGuest) {
			$model = new LoginForm;

			// if it is ajax validation request
			if(isset($_POST['ajax']) && $_POST['ajax'] === 'login-form')
			{
				echo CActiveForm::validate($model);
				Yii::app()->end();
			}

			// collect user input data
			if(isset($_POST['LoginForm']))
			{
				$model->attributes = $_POST['LoginForm'];

				// validate user input and redirect to previous page if valid
				if($model->validate() && $model->login()) {
					if (strpos(Yii::app()->user->returnUrl, '/index.php') !== false)
						$this->redirect(Yii::app()->controller->module->returnUrl);
					else
						$this->redirect(Yii::app()->user->returnUrl);
				}
			}

			// display the login form
			$this->render('login', array('model' => $model));
		} else {
			$this->redirect(Yii::app()->user->returnUrl);
		}
	}

	/**
	 * Logs out the current user and redirect to homepage.
	 */
	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
	}
}
