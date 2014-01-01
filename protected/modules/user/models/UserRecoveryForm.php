<?php

/**
 * UserRecoveryForm class.
 * UserRecoveryForm is the data structure for keeping
 * user recovery form data. It is used by the 'recovery' action of 'UserController'.
 */
class UserRecoveryForm extends CFormModel {
	public $login_or_email;
	public $user_id;

	/**
	 * Declares the validation rules.
	 * The rules state that username or email are required,
	 */
	public function rules()
	{
		return array(
			array('login_or_email', 'required'),
			array('login_or_email', 'match', 'pattern' => '/^[A-Za-z0-9_\.@.-\s,]+$/u',
			      'message' => UserModule::t("Invalid characters (A-z0-9).")),
			array('login_or_email', 'checkexists'),
		);
	}

	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels()
	{
		return array(
			'login_or_email' => UserModule::t("username or email address"),
		);
	}

	public function checkexists($attribute, $params)
	{
		if(!$this->hasErrors())  // we only want to check when no input errors
		{
			// Case insensitive searching!
			$username = strtolower($this->login_or_email);
			$useemail = (strpos($this->login_or_email, "@") ? 1 : 0);

			// Check for an existing user
			if($useemail) {
				$user = User::model()->find('LOWER(email) = ?', array($username));
			} else {
				$user = User::model()->find('LOWER(username) = ?', array($username));
			}

			if($user === null) {
				$this->addError("login_or_email", UserModule::t("The specified username or e-mail address was  not found."));
			} else {
				$this->user_id = $user->id;
			}
		}
	}
}
