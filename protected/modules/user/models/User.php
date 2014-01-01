<?php

/**
 * This is the model class for table "users".
 *
 * The followings are the available columns in table 'users':
 * @property integer $id
 * @property string $username
 * @property string $password
 * @property string $email
 * @property string $activation_key
 * @property integer $superuser
 * @property integer $status
 * @property integer $failed_logins
 * @property string $last_visit
 * @property integer $created_by_id
 * @property string $created_on
 * @property integer $updated_by_id
 * @property string $updated_on
 *
 * The followings are the available model relations:
 * @property Profile $profile
 * @property ArenaGroups[] $arenaGroups
 * @property Arenas[] $arenas
 * @property Contacts[] $contacts
 * @property EventRequests[] $eventRequestsCreated
 * @property EventRequests[] $eventRequestsAcknowledged
 * @property EventRequests[] $eventRequestsAccepted
 * @property EventRequests[] $eventRequestsRejected
 * @property User $createdBy
 * @property User $updatedBy
 */

class User extends CActiveRecord
{
	const STATUS_NOTACTIVATED = 0;
	const STATUS_ACTIVE = 1;
	const STATUS_LOCKED = 2;
	const STATUS_RESET = 3;
	const STATUS_INACTIVE = 4;
	const STATUS_DELETED = 5;
	const STATUS_BANNED = -1;

	/**
	 * Returns the static model of the specified AR class.
	 * @return CActiveRecord the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return Yii::app()->getModule('user')->tableUsers;
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.

		return ((Yii::app()->getModule('user')->isAdmin()) ? array(
			array('username', 'length', 'max' => 20, 'min' => 3,
			      'message' => UserModule::t("Invalid username (length between 3 and 20 characters).")),
			array('username', 'unique',
			      'message' => UserModule::t("Username already exists.")),
			array('username', 'match', 'pattern' => '/^[A-Za-z0-9_\.]+$/u',
			      'message' => UserModule::t("Invalid characters (A-z, 0-9).")),
			array('password', 'length', 'max' => 64, 'min' => 8,
			      'message' => UserModule::t("Invalid password (minimal length 8 characters).")),
			array('password', 'match', 'pattern' => '/(?=^.{8,}$)(?=.*\d)(?=.*[!@#$%^&*]+)(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$/u',
			      'message' => UserModule::t("Password must contain at least one from each set (a-z, A-Z, 0-9, !@#$%^&*)."),
			      'on' => 'newUser'),
			array('email', 'email'),
			array('email', 'unique',
			      'message' => UserModule::t("Email address already exists.")),
			array('status', 'in', 'range' => array(self::STATUS_NOTACTIVATED,
												   self::STATUS_ACTIVE,
												   self::STATUS_LOCKED,
												   self::STATUS_RESET,
												   self::STATUS_INACTIVE,
												   self::STATUS_DELETED,
												   self::STATUS_BANNED)),
			array('superuser', 'in', 'range' => array(0, 1)),
			array('superuser, status', 'numerical', 'integerOnly' => true),
			array('username, email, superuser, status', 'required'),
		) : ((Yii::app()->user->id == $this->id) ?
		     array(
			array('username, email', 'required'),
			array('username', 'length', 'max' => 20, 'min' => 3,
			      'message' => UserModule::t("Invalid username (length between 3 and 20 characters).")),
			array('username', 'unique',
			      'message' => UserModule::t("Username already exists.")),
			array('username', 'match', 'pattern' => '/^[A-Za-z0-9_\.]+$/u',
			      'message' => UserModule::t("Invalid characters (A-z, 0-9).")),
			array('email', 'email'),
			array('email', 'unique',
			      'message' => UserModule::t("Email address already exists.")),
		) : array()));
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		$relations = array(
			'profile' => array(self::HAS_ONE, 'Profile', 'user_id'),
			'createdBy' => array(self::HAS_ONE, 'User', array('id' => 'created_by_id'), 'select' => array('id', 'username', 'status')),
			'updatedBy' => array(self::HAS_ONE, 'User', array('id' => 'updated_by_id'), 'select' => array('id', 'username', 'status')),
            'arenaGroups' => array(self::MANY_MANY, 'ArenaGroups', 'arena_group_users(user_id, arena_group_id)'),
            'arenas' => array(self::MANY_MANY, 'Arenas', 'arena_users(user_id, arena_id)'),
            'contacts' => array(self::HAS_MANY, 'Contacts', 'user_id'),
            'eventRequestsCreated' => array(self::HAS_MANY, 'EventRequests', 'user_id'),
            'eventRequestsAcknowledged' => array(self::HAS_MANY, 'EventRequests', 'acknowledged_by_id'),
            'eventRequestsAccepted' => array(self::HAS_MANY, 'EventRequests', 'accepted_by_id'),
            'eventRequestsRejected' => array(self::HAS_MANY, 'EventRequests', 'rejected_by_id'),
		);
		if (isset(Yii::app()->getModule('user')->relations))
			$relations = array_merge($relations, Yii::app()->getModule('user')->relations);
		return $relations;
	}

	/**
	 * @return array customized attribute labels (name => label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'username' => UserModule::t("Username"),
			'password' => UserModule::t("Password"),
			'verifyPassword' => UserModule::t("Verify Password"),
			'email' => UserModule::t("e-mail"),
			'verifyCode' => UserModule::t("Verification Code"),
			'activation_key' => UserModule::t("Activation key"),
			'superuser' => UserModule::t("Superuser"),
			'status' => UserModule::t("Status"),
			'failed_logins' => UserModule::t("Failed Logins"),
			'last_visit' => UserModule::t("Last Visit"),
			'created_by_id' => UserModule::t("Registered By"),
			'created_on' => UserModule::t("Registered On"),
			'updated_by_id' => UserModule::t("Last Updated By"),
			'updated_on' => UserModule::t("Last Updated On"),
		);
	}

	public function scopes()
	{
		return array(
			'deleted' => array(
				'condition' => 'status = ' . self::STATUS_DELETED,
			),
			'inactive' => array(
				'condition' => 'status = ' . self::STATUS_INACTIVE,
			),
			'reset' => array(
				'condition' => 'status = ' . self::STATUS_RESET,
			),
			'locked' => array(
				'condition' => 'status = ' . self::STATUS_LOCKED,
			),
			'active' => array(
				'condition' => 'status = ' . self::STATUS_ACTIVE,
			),
			'notactivated' => array(
				'condition' => 'status = ' . self::STATUS_NOTACTIVATED,
			),
			'banned' => array(
				'condition' => 'status = ' . self::STATUS_BANNED,
			),
			'superuser' => array(
				'condition' => 'superuser = 1',
			),
			'foractivation' => array(
				'select' => 'id, username, email, activation_key, status, updated_by_id, updated_on',
				'condition' => 'status = ' . self::STATUS_NOTACTIVATED,
			),
			'all' => array(
				'select' => 'id, username, password, email, activation_key, superuser, status, failed_logins, last_visit, created_by_id, created_on, updated_by_id, updated_on',
			),
		);
	}

	public function defaultScope()
	{
		return array(
				'select' => 'id, username, email, superuser, status, failed_logins, last_visit, created_by_id, created_on, updated_by_id, updated_on',
			);
	}

	public static function itemAlias($type, $code = NULL)
	{
		$_items = array(
			'UserStatus' => array(
				self::STATUS_NOTACTIVATED => UserModule::t('Not Activated'),
				self::STATUS_ACTIVE => UserModule::t('Active'),
				self::STATUS_LOCKED => UserModule::t('Locked'),
				self::STATUS_RESET => UserModule::t('Reset'),
				self::STATUS_INACTIVE => UserModule::t('Inactive'),
				self::STATUS_DELETED => UserModule::t('Deleted'),
				self::STATUS_BANNED => UserModule::t('Banned'),
			),
			'AdminStatus' => array(
				'0' => UserModule::t('No'),
				'1' => UserModule::t('Yes'),
			),
		);

		if(isset($code))
			return isset($_items[$type][$code]) ? $_items[$type][$code] : false;
		else
			return isset($_items[$type]) ? $_items[$type] : false;
	}

    /**
     * @desc Retrieves a list of models based on the current search/filter conditions.
     *
     * Typical usecase:
     * - Initialize the model fields with values from filter form.
     * - Execute this method to get CActiveDataProvider instance which will filter
     * models according to data in model fields.
     * - Pass data provider to CGridView, CListView or any similar widget.
     *
     * @return CActiveDataProvider the data provider that can return the models
     * based on the search/filter conditions.
     */
    public function search()
    {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('username', $this->username,true);
        $criteria->compare('email', $this->email,true);
        $criteria->compare('activation_key', $this->activation_key,true);
        $criteria->compare('superuser', $this->superuser);
        $criteria->compare('status', $this->status);
        $criteria->compare('failed_logins', $this->failed_logins);
        $criteria->compare('last_visit', $this->last_visit,true);
        $criteria->compare('created_by_id', $this->created_by_id);
        $criteria->compare('created_on', $this->created_on,true);
        $criteria->compare('updated_by_id', $this->updated_by_id);
        $criteria->compare('updated_on', $this->updated_on,true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * @desc Sets the audit field values automatically
     * @return bool
     */
	public function beforeSave()
	{
		if($this->isNewRecord) {
			$this->created_on = new CDbExpression('NOW()');

			if(Yii::app()->user->id) {
				$this->created_by_id = Yii::app()->user->id;
			}
		}

		$this->updated_on = new CDbExpression('NOW()');

		if(Yii::app()->user->id) {
				$this->updated_by_id = Yii::app()->user->id;
		}

		return parent::beforeSave();
	}

   /**
     * @desc Compares the passed in password to the hashed password
     * @param string $password
     * @return bool
     */
	public function verifyPassword($password)
	{
		return CPasswordHelper::verifyPassword($password, $this->password);
	}

    /**
     * @desc Hashes the passed in password using Blowfish
     * @param string $password
     * @param int $cost
     * @return string The 64 character hashed password
     */
	public function hashPassword($password, $cost = 13)
	{
		return CPasswordHelper::hashPassword($password, $cost);
	}

    /**
     * @desc Validates that the passed in password meets complexity
     * requirements before hashing and storing the password. This
     * function also generates a new activation key if the user's
     * status is self::STATUS_NOTACTIVATED
     * @param string $password
     * @param bool $save true to save the record
     * @return bool
     */
	public function changePassword($password, $save = false)
	{
		$previousPassword = $this->password;
		$this->password = $password;

		// validate the password before we go and change it!
		$oldScenario = $this->scenario;
		$this->scenario = 'newPassword';

		if(!$this->validate()) {
			$this->password = $previousPassword;
			return false;
		}

		$this->scenario = $oldScenario;

		// We change the activation key if the user has not
		// activated their account yet and we send a new e-mail.
		if($this->status == self::STATUS_NOTACTIVATED) {
			$this->activation_key = hash(Yii::app()->getModule('user')->hash, microtime() . $password);
			$this->sendMailMessage('activation');
		}

		$this->password = $this->hashPassword($password);

		if($save) {
			return $this->save(true, array('activation_key', 'password', 'updated_on', 'updated_by_id'));
		} else {
			return true;
		}
	}

    /**
     * @desc Validates that the new password meets complexity
     * requirements before hashing and storing the password. This
     * function also generates an activation key
     * @param bool $save true to save the record
     * @return bool
     */
	public function hashNewUserPassword($save = false)
	{
		if($this->isNewRecord) {
			// Ensure that the password meets complexity requirements
			$oldScenario = $this->scenario;
			$this->scenario = 'newPassword';

			if(!$this->validate()) {
				return false;
			}

			$this->scenario = $oldScenario;

			$this->activation_key = hash(Yii::app()->getModule('user')->hash, microtime() . $this->password);
			$this->password = $this->hashPassword($this->password);
		}

		if($save) {
			return $this->save(true, array('activation_key', 'password', 'created_on', 'created_by_id', 'updated_on', 'updated_by_id'));
		} else {
			return true;
		}
	}

    /**
     * @desc Locks the account if it is active and
     * exceeds the failedLogins
     * @return bool
     */
	public function lockUser()
	{
		if($this->status == self::STATUS_ACTIVE && $this->failed_logins > Yii::app()->getModule('user')->failedLogins) {
			// Lock the user account!
			$this->status = self::STATUS_LOCKED;

			return true;
		}

		return false;
	}

     /**
     * @desc Unlocks the account if it is locked and
     * resets the failed_logins to zero
     * @return bool
     */
	public function unlockUser()
	{
		if($this->status == self::STATUS_LOCKED) {
			// Lock the user account!
			$this->status = self::STATUS_ACTIVE;
			$this->failed_logins = 0;

			return true;
		}

		return false;
	}

    /**
     * @desc Locks the account if it is active and
     * exceeds the failedLogins
     * @return bool
     */
	public function inactiveUser()
	{
		if($this->last_visit == '0000-00-00 00:00:00') {
			return false;
		}

		$dtLastVisit = DateTime::createFromFormat('Y-m-d H:i:s', $this->last_visit);
		$dtCurrentTime = DateTime::createFromFormat('m-d-Y H:i:s', 'now');
		$dtiDays = $dtLastVisit->diff(dtCurrentTime, true);


		if($this->status == self::STATUS_ACTIVE && $dtiDays->days > Yii::app()->getModule('user')->daysSinceLastVisit) {
			// Mark the user account inactive!
			$this->status = self::STATUS_INACTIVE;

			return true;
		}

		return false;
	}

    /**
     * @desc Resets the account if it is inactive or locked
     * @return bool
     */
	public function resetUser()
	{
		if($this->status == self::STATUS_LOCKED ||
		   $this->status == self::STATUS_INACTIVE ||
		   $this->status == self::STATUS_ACTIVE) {
			// Reset the user account!
			$this->status = self::STATUS_RESET;

			return true;
		}

		return false;
	}

	/**
     * @desc Bans the account
     * @return bool
     */
	public function banUser()
	{
		// Ban the user account!
		$this->status = self::STATUS_BANNED;

		return true;
	}

    /**
     * @desc Marks the account as deleted
     * @return bool
     */
	public function deleteUser()
	{
		// Delete the user account!
		$this->status = self::STATUS_DELETED;

		return true;
	}

    /**
     * @desc Determines if the account is not activated
     * @return bool
     */
	public function isNotActivated()
	{
		if($this->status == self::STATUS_NOTACTIVATED) {
			return true;
		} else {
			return false;
		}
	}

    /**
     * @desc Determines if the account is active
     * @return bool
     */
	public function isActive()
	{
		if($this->status == self::STATUS_ACTIVE) {
			return true;
		} else {
			return false;
		}
	}

    /**
     * @desc Determines if the account is locked
     * @return bool
     */
	public function isLocked()
	{
		if($this->status == self::STATUS_LOCKED) {
			return true;
		} else {
			return false;
		}
	}

    /**
     * @desc Determines if the account is reset
     * @return bool
     */
	public function isReset()
	{
		if($this->status == self::STATUS_RESET) {
			return true;
		} else {
			return false;
		}
	}

    /**
     * @desc Determines if the account is inactive
     * @return bool
     */
	public function isInactive()
	{
		if($this->status == self::STATUS_INACTIVE) {
			return true;
		} else {
			return false;
		}
	}

    /**
     * @desc Determines if the account is deleted
     * @return bool
     */
	public function isDeleted()
	{
		if($this->status == self::STATUS_DELETED) {
			return true;
		} else {
			return false;
		}
	}

    /**
     * @desc Determines if the account is banned
     * @return bool
     */
	public function isBanned()
	{
		if($this->status == self::STATUS_BANNED) {
			return true;
		} else {
			return false;
		}
	}

    /**
     * @desc Increments the failed_logins count and
     * calls lockUser
     * @param bool $save true to save the record
     * @return bool
     */
	public function loginFailed($save = false)
	{
		$this->failed_logins += 1;

		$this->lockUser();

		if($save) {
			return $this->save(true, array('status', 'failed_logins', 'updated_on', 'updated_by_id'));
		} else {
			return true;
		}
	}

    /**
     * @desc Updates last_visit to NOW() and resets failed_logins to 0
     * @param bool $save true to save the record
     * @return bool
     */
	public function loginSuccessful($save = false)
	{
		$this->failed_logins = 0;
		$this->last_visit = new CDbExpression('NOW()');

		if($save) {
			return $this->save(true, array('failed_logins', 'last_visit', 'updated_on', 'updated_by_id'));
		} else {
			return true;
		}
	}

    /**
     * @desc Activates the user's account!
     * @param string $activation_key
     * @param bool $save
     * @return bool
     */
	public function activateAccount($activation_key, $save = false)
	{
		if($this->status == self::STATUS_NOTACTIVATED && $this->activation_key == $activation_key) {
			// Reset the activation key so that it cannot be re-used!
			$this->activation_key = hash(Yii::app()->getModule('user')->hash, microtime());;
			$this->status = self::STATUS_ACTIVE;

			if($save) {
				return $this->save(true, array('activation_key', 'status', 'updated_on', 'updated_by_id'));
			} else {
				return true;
			}
		} else {
			return false;
		}
	}
}
