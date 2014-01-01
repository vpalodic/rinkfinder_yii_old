<?php

/**
 * UserIdentity represents the data needed to identify a user.
 * It contains the authentication method that checks if the provided
 * data can identify the user.
 */
class UserIdentity extends CUserIdentity
{
	private $_id;
	const ERROR_EMAIL_INVALID = 3;
	const ERROR_STATUS_NOTACTIVATED = 4;
	const ERROR_STATUS_LOCKED = 5;
	const ERROR_STATUS_RESET = 6;
	const ERROR_STATUS_INACTIVE = 7;
	const ERROR_STATUS_DELETED = 8;
	const ERROR_STATUS_BANNED = 9;
	const ERROR_STATUS_UNKNOWN = 99;

	/**
	 * Authenticates a user by either username or e-mail address.
	 * Searching is case insensitive!
	 * Uses CPasswordHelper which uses the Blowfish Crypto Algo.
	 * @return boolean whether authentication succeeds.
	 */
	public function authenticate()
	{
		// Case insensitive searching!
		$username = strtolower($this->username);
		$useemail = (strpos($this->username, "@") ? 1 : 0);

		// Check for an existing user
		if($useemail) {
			$user = User::model()->all()->find('LOWER(email) = ?', array($username));
		} else {
			$user = User::model()->all()->find('LOWER(username) = ?', array($username));
		}
		if($user === null) {
			if($useemail) {
				$this->errorCode = self::ERROR_EMAIL_INVALID;
			} else {
				$this->errorCode = self::ERROR_USERNAME_INVALID;
			}
		} else {
			// We have a valid user account that we need to authenticate!
			if(!$user->verifyPassword($this->password)) {
				$this->errorCode = self::ERROR_PASSWORD_INVALID;
				$user->loginFailed(true);
			} else {
				switch($user->status) {
					case User::STATUS_NOTACTIVATED:
						if(Yii::app()->getModule('user')->loginNotActiv == false) {
							$this->errorCode = self::ERROR_STATUS_NOTACTIVATED;
						} else {
							$this->_id = $user->id;
							$this->username = $user->username;
							$this->errorCode = self::ERROR_NONE;
							$user->loginSuccessful(true);
						}
						break;
					case User::STATUS_LOCKED:
						$this->errorCode = self::ERROR_STATUS_LOCKED;
						break;
					case User::STATUS_RESET:
						$this->errorCode = self::ERROR_STATUS_RESET;
						break;
					case User::STATUS_INACTIVE:
						$this->errorCode = self::ERROR_STATUS_INACTIVE;
						break;
					case User::STATUS_DELETED:
						$this->errorCode = self::ERROR_STATUS_DELETED;
						break;
					case User::STATUS_BANNED:
						$this->errorCode = self::ERROR_STATUS_BANNED;
						break;
					case User::STATUS_ACTIVE:
						$this->_id = $user->id;
						$this->username = $user->username;
						$this->errorCode = self::ERROR_NONE;
						$user->loginSuccessful(true);
						break;
					default:
						//$this->errorCode = self::ERROR_NONE;
						$this->errorCode = self::ERROR_STATUS_UNKNOWN;
						break;
				}
			}
		}

		return !$this->errorCode;
	}

    /**
    * @return integer the ID of the user record
    */
	public function getId()
	{
		return $this->_id;
	}

}
