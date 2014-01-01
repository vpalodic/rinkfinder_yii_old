<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');
//Yii::setPathOfAlias('bootstrap', dirname(__FILE__) . '/../extensions/bootstrap');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
	'basePath' => dirname(__FILE__).DIRECTORY_SEPARATOR.'..',

	'name' => 'Rinkfinder',

//    'theme' => 'bootstrap',
//    'theme' => 'classic',

    'aliases' => array(
        'bootstrap' => realpath(__DIR__ . '/../extensions/bootstrap'), // change this if necessary

        'yiiwheels' => realpath(__DIR__ . '/../extensions/yiiwheels'), // change if necessary
    ),

	// preloading 'log' component
	'preload' => array('log'),

	// autoloading model and component classes
	'import' => array(
		'application.models.*',
		'application.components.*',
        'ext.YiiMailer.YiiMailer',
        'application.modules.user.*',
        'application.modules.user.models.*',
        'application.modules.user.components.*',
        'application.modules.auth.*',
        'application.modules.auth.components.*',
        'bootstrap.helpers.TbHtml',
	),

	'modules' => array(
        'auth' => array(
            'strictMode' => true, // when enabled authorization items cannot be assigned children of the same type.
            'userClass' => 'User', // the name of the user model class.
            'userIdColumn' => 'id', // the name of the user id column.
            'userNameColumn' => 'username', // the name of the user name column.
//            if you use yii-auth(1.7.0), use one of the below defaultLayout.
            'defaultLayout' => 'application.modules.auth.views.layouts.main', // the layout used by the module.
//            'defaultLayout' => 'webroot.themes.bootstrap.views.layouts.main', // the layout used by bootstrap theme.
            'viewDir' => null, // the path to view files to use with this module.,
        ),

        'user' => array(
            'tableUsers' => 'users',
            'tableProfiles' => 'profiles',
            'tableProfileFields' => 'profile_fields',
            # users listed per page
            'user_page_size' => 10,
            # fields listed per page
            'fields_page_size' => 10,
            # hash algorithm to use
            'hash' => 'sha256',
            # failed logins after which account is locked
            'failedLogins' => 6,
            # send activation email
            'sendActivationMail' => true,
            # allow access for non-activated users
            'loginNotActiv' => true,
            # activate user on registration (only sendActivationMail = false)
            'activeAfterRegister' => false,
            # automatically login from registration
            'autoLogin' => true,
            # registration path
            'registrationUrl' => array('/user/registration'),
            # recovery password path
            'recoveryUrl' => array('/user/recovery'),
            # login form path
            'loginUrl' => array('/site/login'),
            # logout form path
            'logoutUrl' => array('/site/logout'),
            # page after login
            'returnUrl' => array('/user/profile'),
            # page after logout
            'returnLogoutUrl' => array('/site/login'),
            # page for user message
            'messageUrl' => array('/user/message'),
            # page for user activation
            'activationUrl' => array('/user/activation')
        ),

		'gii' => array(
            'generatorPaths' => array(
                'bootstrap.gii',
            ),
			'class' => 'system.gii.GiiModule',
			'password' => 'Rinkfinder2013#',
			// If removed, Gii defaults to localhost only. Edit carefully to taste.
			'ipFilters' => array('127.0.0.1','::1'),
		),

	),

	// application components
	'components' => array(

        // yii-user
		'user' => array(
			// enable cookie-based authentication
			'allowAutoLogin' => true,
            'class' => 'AuthWebUser',
            'loginUrl' => array('/site/login'),
            'admins' => array('sysadmin'), // users with full access
		),

        // yiistrap configuration
        'bootstrap' => array(
            'class' => 'bootstrap.components.TbApi',
        ),

        // yiiwheels configuration
        'yiiwheels' => array(
            'class' => 'yiiwheels.YiiWheels',
        ),

        'authManager' => array(
            'class' => 'auth.components.CachedDbAuthManager',
            'cachingDuration' => 3600,
            'behaviors' => array('auth' => array('class' => 'auth.components.AuthBehavior',),
                                 ),
        ),

		'urlManager' => array(
			'urlFormat' => 'path',
            'showScriptName' => false,
			'rules' => array(
				'<controller:\w+>/<id:\d+>' => '<controller>/view',
				'<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
				'<controller:\w+>/<action:\w+>' => '<controller>/<action>',
                '<module:\w+>/<controller:\w+>/<action:\w+>/<id:\d+>' => '<module>/<controller>/<action>',
                '<module:\w+>/<controller:\w+>/<action:\w+>' => '<module>/<controller>/<action>',
			),
		),

		'db' => array(
			'connectionString' => 'mysql:host=127.0.0.1;dbname=miama862_rinkfinder',
			'emulatePrepare' => true,
			'username' => 'miama862_webdev',
			'password' => 'Rinkfinder2013',
			'charset' => 'utf8',
		),

		'errorHandler' => array(
			// use 'site/error' action to display errors
			'errorAction' => 'site/error',
		),

		'log' => array(
			'class' => 'CLogRouter',
			'routes' => array(
				array(
					'class' => 'CFileLogRoute',
					'levels' => 'error, warning',
				),
				// uncomment the following to show log messages on web pages
				array(
					'class' => 'CWebLogRoute',
                    'enabled' => YII_DEBUG,
				),

			),
		),
	),

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params' => array(
		// this is used in contact page
		'adminEmail' => 'webmaster@rinkfinder.com',
        'defaultController' => 'site',
	),

    'behaviors' => array('mail' => array('class' => 'application.components.MailBehavior'),
                         'captcha' => array('class' => 'application.components.CaptchaBehavior')
    ),
);
