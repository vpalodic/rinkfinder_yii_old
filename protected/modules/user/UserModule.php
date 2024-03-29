<?php
/**
 * Yii-User module
 *
 * @author Mikhail Mangushev <mishamx@gmail.com>
 * @link http://yii-user.googlecode.com/
 * @license http://www.opensource.org/licenses/bsd-license.php
 * @version $Id: UserModule.php 105 2011-02-16 13:05:56Z mishamx $
 */

class UserModule extends CWebModule
{
	/**
	 * @var int
	 * @desc items on page
	 */
	public $user_page_size = 10;

	/**
	 * @var int
	 * @desc items on page
	 */
	public $fields_page_size = 10;

	/**
	 * @var string
	 * @desc hash method (md5,sha1 or algo hash function http://www.php.net/manual/en/function.hash.php)
	 */
	public $hash = 'md5';

	/**
	 * @var int
	 * @desc number of failed logins allowed before locking account
	 */
	public $failedLogins = 4;

	/**
	 * @var int
	 * @desc number of days since last visit before marking the account inactive
	 */
	public $daysSinceLastVisit = 90;

	/**
	 * @var boolean
	 * @desc use email for activation user account
	 */
	public $sendActivationMail = true;

	/**
	 * @var boolean
	 * @desc allow auth for is not active user
	 */
	public $loginNotActiv = false;

	/**
	 * @var boolean
	 * @desc activate user on registration (only $sendActivationMail = false)
	 */
	public $activeAfterRegister = false;

	/**
	 * @var boolean
	 * @desc login after registration (need loginNotActiv or activeAfterRegister = true)
	 */
	public $autoLogin = true;

	public $registrationUrl = array("/user/registration");
	public $recoveryUrl = array("/user/recovery/recovery");
	public $activationUrl = array("/user/activation/activation");
	public $loginUrl = array("/user/login");
	public $messageUrl = array("/user/message");
	public $logoutUrl = array("/user/logout");
	public $profileUrl = array("/user/profile");
	public $editProfileUrl = array("/user/profile/edit");
	public $changePasswordUrl = array("/user/profile/changepassword");
	public $returnUrl = array("/user/profile");
	public $returnLogoutUrl = array("/user/login");
    public $listUsersUrl = array("/user");
    public $manageUsersUrl = array("/user/admin");
    public $createUserUrl = array("/user/admin/create");
    public $manageProfileFieldsUrl = array("/user/profileField/admin");
    public $createProfileFieldUrl = array("/user/profileField/create");

	public $fieldsMessage = '';

	/**
	 * @var array
	 * @desc User model relation from other models
	 * @see http://www.yiiframework.com/doc/guide/database.arr
	 */
	public $relations = array();

	/**
	 * @var array
	 * @desc Profile model relation from other models
	 */
	public $profileRelations = array();

	/**
	 * @var boolean
	 */
	public $captcha = array('registration' => true);

	/**
	 * @var boolean
	 */
	//public $cacheEnable = false;

	public $tableUsers = '{{users}}';
	public $tableProfiles = '{{profiles}}';
	public $tableProfileFields = '{{profile_fields}}';

	static private $_user;
	static private $_admin;
	static private $_admins;

	/**
	 * @var array
	 * @desc Behaviors for models
	 */
	public $componentBehaviors = array();

	public function init()
	{
		// this method is called when the module is being created
		// you may place code here to customize the module or the application

		// import the module-level models and components
		$this->setImport(array(
			'user.models.*',
			'user.components.*',
		));
	}

	public function getBehaviorsFor($componentName){
            if(isset($this->componentBehaviors[$componentName])) {
                return $this->componentBehaviors[$componentName];
            } else {
                return array();
            }
	}

	public function beforeControllerAction($controller, $action)
	{
        if(parent::beforeControllerAction($controller, $action)) {
            // this method is called before any module controller action is performed
            // you may place customized code here
            return true;
	    } else {
                return false;
	    }
	}

	/**
	 * @param $str
	 * @param $params
	 * @param $dic
	 * @return string
	 */
	public static function t($str = '', $params = array(), $dic = 'user')
    {
        return Yii::t("UserModule." . $dic, $str, $params);
	}

	/**
	 * Return admin status.
	 * @return boolean
	 */
	public static function isAdmin()
    {
        if(Yii::app()->user->isGuest) {
            return false;
        } else {
            if(!isset(self::$_admin)) {
                if(isset(self::user()->superuser) && self::user()->superuser) {
                    self::$_admin = true;
                } else {
                    self::$_admin = false;
                }
            }
            return self::$_admin;
        }
	}

	/**
	 * @desc Return admins.
	 * @return array syperusers names
	 */
	public static function getAdmins()
    {
        if(!self::$_admins) {
            $admins = User::model()->active()->superuser()->findAll();

            $return_name = array();

            foreach($admins as $admin) {
                array_push($return_name, $admin->username);
            }

            self::$_admins = $return_name;
        }
        return self::$_admins;
	}

	/**
	 * @desc Return safe user data.
	 * @param user id not required
	 * @return user object or false
	 */
	public static function user($id = 0)
    {
        if($id) {
            return User::model()->all()->findbyPk($id);
        } else if(Yii::app()->user->isGuest) {
            return false;
        } else if(!self::$_user) {
            self::$_user = User::model()->all()->findbyPk(Yii::app()->user->id);
        }
        return self::$_user;
	}

	/**
	 * Return safe user data.
	 * @return user object or false
	 */
	public function users()
    {
		return User;
	}
}
