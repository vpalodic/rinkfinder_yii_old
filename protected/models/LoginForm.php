<?php

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class LoginForm extends CFormModel
{
	public $username;
	public $password;
	public $rememberMe;

	private $_identity;

	/**
	 * Declares the validation rules.
	 * The rules state that username and password are required,
	 * and password needs to be authenticated.
	 */
	public function rules()
	{
		return array(
			// username and password are required
			array('username, password', 'required'),
			// rememberMe needs to be a boolean
			array('rememberMe', 'boolean'),
			// password needs to be authenticated
			array('password', 'authenticate'),
		);
	}

	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels()
	{
		return array(
			'rememberMe' => 'Remember me next time',
		);
	}

	/**
	 * Authenticates the password.
	 * This is the 'authenticate' validator as declared in rules().
	 */
	public function authenticate($attribute, $params)
	{
		if(!$this->hasErrors())  // we only want to authenticate when no input errors
		{
			$this->_identity = new UserIdentity($this->username, $this->password);

			$this->_identity->authenticate();

			switch($this->_identity->errorCode)
			{
				case UserIdentity::ERROR_NONE:
					break;
				case UserIdentity::ERROR_PASSWORD_INVALID:
					$this->addError("password", UserModule::t("Invalid password."));
					break;
				case UserIdentity::ERROR_USERNAME_INVALID:
					$this->addError("username", UserModule::t("Invalid username."));
					break;
				case UserIdentity::ERROR_EMAIL_INVALID:
					$this->addError("username", UserModule::t("Invalid e-mail address."));
					break;
				case UserIdentity::ERROR_STATUS_NOTACTIVATED:
					$this->addError("username", UserModule::t("Your account has not been activated."));
					break;
				case UserIdentity::ERROR_STATUS_LOCKED:
					$this->addError("username", UserModule::t("Your account is locked."));
					break;
				case UserIdentity::ERROR_STATUS_RESET:
					$this->addError("username", UserModule::t("Your account has been reset."));
					break;
				case UserIdentity::ERROR_STATUS_INACTIVE:
					$this->addError("username", UserModule::t("Your account is inactive."));
					break;
				case UserIdentity::ERROR_STATUS_DELETED:
					$this->addError("username", UserModule::t("Your account has been deleted."));
					break;
				case UserIdentity::ERROR_STATUS_BANNED:
					$this->addError("username", UserModule::t("Your account is blocked."));
					break;
				case UserIdentity::ERROR_STATUS_UNKNOWN:
					$this->addError("username", UserModule::t("Unknown account status error."));
					break;
			}
		}
	}

	/**
	 * Logs in the user using the given username and password in the model.
	 * @return boolean whether login is successful
	 */
	public function login()
	{
		$retVal = false;

		if($this->_identity === null)
		{
			$this->_identity = new UserIdentity($this->username, $this->password);
			$this->_identity->authenticate();
		}

		switch($this->_identity->errorCode)
		{
			case UserIdentity::ERROR_NONE:
				$duration = $this->rememberMe ? 3600 * 24 * 7 : 0; // 7 days
				Yii::app()->user->login($this->_identity, $duration);
				$retVal = true;
				break;
		}

		return $retVal;
	}
}
