<?php

class RegistrationController extends Controller
{
	public $defaultAction = 'registration';

	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return (isset($_POST['ajax']) && $_POST['ajax'] === 'registration-form') ? array() : array(
			'captcha'=>array('class' => 'CCaptchaAction',
							 'backColor' => 0xFFFFFF,
			),
		);
	}

	/**
	 * Registration user
	 */
	public function actionRegistration()
	{
		$model = new RegistrationForm;
		$profile = new Profile;
		$profile->regMode = true;

		// ajax validator
		if(isset($_POST['ajax']) && $_POST['ajax'] === 'registration-form') {
			echo UActiveForm::validate(array($model, $profile));
			Yii::app()->end();
		}

		if(Yii::app()->user->id) {
			$this->redirect(Yii::app()->controller->module->profileUrl);
		} else {
			if(isset($_POST['RegistrationForm'])) {
				$model->attributes = $_POST['RegistrationForm'];
				$profile->attributes = ((isset($_POST['Profile']) ? $_POST['Profile'] : array()));

				if($model->validate() && $profile->validate())
				{
					$sourcePassword = $model->password;
					$model->hashNewUserPassword();
					$model->verifyPassword = $model->password;
					$model->status = ((Yii::app()->controller->module->activeAfterRegister) ?
									  User::STATUS_ACTIVE :
									  User::STATUS_NOTACTIVATED);

					if($model->save()) {
						$user=Yii::app()->controller->module->user();

						if($user) {
							$model->created_by_id = $user->id;
							$model->updated_by_id = $user->id;
							$model->save();
						} else {
							$model->created_by_id = $model->id;
							$model->updated_by_id = $model->id;
							$model->save();
						}

						$profile->user_id = $model->id;
						$profile->save();

						if (Yii::app()->controller->module->sendActivationMail) {
							$activation_url = $this->createAbsoluteUrl(Yii::app()->controller->module->activationUrl[0],
																	   array("activation_key" => $model->activation_key,
																			 "email" => $model->email));
							Yii::app()->sendMail('',
												 $model->email,
												 UserModule::t("Your {site_name} registration",
															   array('{site_name}' => Yii::app()->name)),
												 UserModule::t("Please activate your account by going to {activation_url}",
															   array('{activation_url}' => $activation_url)));
						}

						if (!Yii::app()->controller->module->activeAfterRegister &&
						    !Yii::app()->controller->module->sendActivationMail) {
							Yii::app()->user->setFlash(TbHtml::ALERT_COLOR_SUCCESS,
													   UserModule::t("Thank you for registering with {site_name}. Please contact your administrator to activate your account",
																	 array('{site_name}' => Yii::app()->name)));
						} elseif(Yii::app()->controller->module->activeAfterRegister &&
							 !Yii::app()->controller->module->sendActivationMail) {
							Yii::app()->user->setFlash(TbHtml::ALERT_COLOR_SUCCESS,
													   UserModule::t("Thank you for registering with {site_name}. Please {{login}}.",
																	 array('{site_name}' => Yii::app()->name,
																		   '{{login}}' => CHtml::link(UserModule::t('Login'), Yii::app()->controller->module->loginUrl))));
						} elseif(Yii::app()->controller->module->loginNotActiv) {
							Yii::app()->user->setFlash(TbHtml::ALERT_COLOR_SUCCESS,
													   UserModule::t("Thank you for registering with {site_name}. Please check your e-mail or {{login}}.",
																	 array('{site_name}' => Yii::app()->name,
																		   '{{login}}' => CHtml::link(UserModule::t('Login'), Yii::app()->controller->module->loginUrl))));
						} else {
							Yii::app()->user->setFlash(TbHtml::ALERT_COLOR_SUCCESS,
													   UserModule::t("Thank you for registering with {site_name}. Please check your e-mail to activate your account.",
																	 array('{site_name}' => Yii::app()->name)));
						}

						if((Yii::app()->controller->module->loginNotActiv ||
						     (Yii::app()->controller->module->activeAfterRegister &&
						      Yii::app()->controller->module->sendActivationMail == false)) &&
						    Yii::app()->controller->module->autoLogin) {
							$identity = new UserIdentity($model->username, $sourcePassword);
							if($identity->authenticate()) {
								Yii::app()->user->login($identity, 0);
								$this->redirect(Yii::app()->controller->module->returnUrl);
							}
						} else {
							$this->refresh();
						}
					}
				} else {
					$profile->validate();
				}
			}
			$this->render('/user/registration', array('model' => $model, 'profile' => $profile));
		}
	}
}
