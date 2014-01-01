<?php

/**
 * Yii-User-Import module
 * for the Yii-User module by Mikhail Mangushev (http://yii-user.googlecode.com/)
 *
 * @author Rob Rhyne <rob.rhyne@gmail.com>
 * @link http://robertrhyne.com/
 * @license http://www.opensource.org/licenses/bsd-license.php
 * @version $Id: UserImport.php 001 2011-11-13 13:05:56Z rrhyne $
 */


class UserImportModule extends CWebModule
{
	public function init()
	{
		// this method is called when the module is being created
		// you may place code here to customize the module or the application

		// import the module-level models and components
		$this->setImport(array(
			'userImport.models.*',
			'userImport.components.*',
		));
	}

	public function beforeControllerAction($controller, $action)
	{
		if(parent::beforeControllerAction($controller, $action))
		{
			// this method is called before any module controller action is performed
			// you may place customized code here
			return true;
		}
		else
			return false;
	}
}
