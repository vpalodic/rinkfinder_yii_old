<?php
/**
 * RegistrationForm class.
 * RegistrationForm is the data structure for keeping
 * user registration form data. It is used by the 'registration' action of 'UserController'.
 */
class RegistrationForm extends User {
	public $verifyPassword;
	public $verifyCode;

	public function rules() {
		$rules = array(
			array('username, password, verifyPassword, email', 'required'),
			array('username', 'length', 'max' => 20, 'min' => 3,
			      'message' => UserModule::t("Invalid username (length between 3 and 20 characters).")),
			array('username', 'unique',
			      'message' => UserModule::t("Usename already exists.")),
			array('username', 'match', 'pattern' => '/^[A-Za-z0-9_\.]+$/u',
			      'message' => UserModule::t("Invalid characters (A-z0-9).")),
			array('password', 'length', 'max' => 64, 'min' => 8,
			      'message' => UserModule::t("Invalid password (minimal length 8 symbols).")),
			array('password', 'match', 'pattern' => '/(?=^.{8,}$)(?=.*\d)(?=.*[!@#$%^&*]+)(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$/u',
			      'message' => UserModule::t("Password must contain at least one from each set (a-z, A-Z, 0-9, !@#$%^&*).")),
			array('email', 'email'),
			array('email', 'unique', 'message' => UserModule::t("Email address already exists.")),
			array('verifyPassword', 'compare', 'compareAttribute' => 'password', 'message' => UserModule::t("Passwords do not match.")),
		);
		if(isset($_POST['ajax']) && $_POST['ajax'] === 'registration-form') {
			return $rules;
		}
		else {
			array_push($rules, array('verifyCode', 'captcha', 'allowEmpty' => !Yii::app()->doCaptcha('registration')));
		}

		return $rules;
	}
}
