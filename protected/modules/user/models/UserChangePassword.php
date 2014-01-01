<?php
/**
 * UserChangePassword class.
 * UserChangePassword is the data structure for keeping
 * user change password form data. It is used by the 'changepassword' action of 'UserController'.
 */
class UserChangePassword extends CFormModel {
	public $password;
	public $verifyPassword;
	
	public function rules() {
		return array(
			array('password, verifyPassword', 'required'),
			array('password', 'length', 'max' => 64, 'min' => 8,
			      'message' => UserModule::t("Invalid password (minimal length 8 symbols).")),
			array('password', 'match', 'pattern' => '/(?=^.{8,}$)(?=.*\d)(?=.*[!@#$%^&*]+)(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$/u',
			      'message' => UserModule::t("Password must contain at least one from each set (a-z, A-Z, 0-9, !@#$%^&*).")),
			array('verifyPassword', 'compare', 'compareAttribute' => 'password',
			      'message' => UserModule::t("Passwords do not match.")),
		);
	}

	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels()
	{
		return array(
			'password' => UserModule::t("Password"),
			'verifyPassword' => UserModule::t("Confirm Password"),
		);
	}
} 