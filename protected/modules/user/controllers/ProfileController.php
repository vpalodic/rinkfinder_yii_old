<?php

class ProfileController extends Controller
{
	public $defaultAction = 'profile';

	/**
	 * @var CActiveRecord the currently loaded data model instance.
	 */
	private $_model;
	/**
	 * Shows a particular model.
	 */
	public function actionProfile()
	{
		$model = $this->loadUser();

		$this->render('profile', array(
			'model' => $model,
			'profile' => $model->profile,
			));
	}


	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionEdit()
	{
		$model = $this->loadUser();
		$profile = $model->profile;

		// ajax validator
		if(isset($_POST['ajax']) && $_POST['ajax'] === 'profile-form')
		{
			echo UActiveForm::validate(array($model, $profile));
			Yii::app()->end();
		}

		if(isset($_POST['User']) && isset($_POST['Profile']))
		{
			$model->attributes = $_POST['User'];
			$profile->attributes = $_POST['Profile'];

			if($model->validate() && $profile->validate()) {
				$model->save();
				$profile->save();
				Yii::app()->user->setFlash(TbHtml::ALERT_COLOR_SUCCESS, UserModule::t("Your changes have been saved."));
				$this->redirect(Yii::app()->controller->module->profileUrl);
			} else {
				$profile->validate();
				Yii::app()->user->setFlash(TbHtml::ALERT_COLOR_ERROR, UserModule::t("Unable to save your changes."));
			}
		}

		$this->render('edit', array(
			'model' => $model,
			'profile' => $profile,
			));
	}

	/**
	 * Change password
	 */
	public function actionChangepassword() {
		$model = new UserChangePassword;
		if(Yii::app()->user->id) {
			// ajax validator
			if(isset($_POST['ajax']) && $_POST['ajax'] === 'changepassword-form')
			{
				echo UActiveForm::validate($model);
				Yii::app()->end();
			}

			if(isset($_POST['UserChangePassword'])) {
					$model->attributes = $_POST['UserChangePassword'];
					if($model->validate()) {
						$new_password = User::model()->all()->findbyPk(Yii::app()->user->id);

						if($new_password->changePassword($model->password)) {
							Yii::app()->user->setFlash(TbHtml::ALERT_COLOR_SUCCESS, UserModule::t("Your password has been changed."));
						} else {
							Yii::app()->user->setFlash(TbHtml::ALERT_COLOR_ERROR, UserModule::t("Unable to change your password."));
						}

						$this->redirect(array("profile"));
					}
			}

			$this->render('changepassword', array('model' => $model));
		}
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the primary key value. Defaults to null, meaning using the 'id' GET variable
	 */
	public function loadUser()
	{
		if($this->_model === null)
		{
			if(Yii::app()->user->id) {
				$this->_model = Yii::app()->controller->module->user();
			}
			if($this->_model === null) {
				$this->redirect(Yii::app()->controller->module->loginUrl);
			}
		}
		
		return $this->_model;
	}
}
