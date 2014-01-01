<?php

class ActivationController extends Controller
{
	public $defaultAction = 'activation';

	/**
	 * Activate the user account
	 */
	public function actionActivation()
	{
		$email = (isset($_GET['email']) ? $_GET['email'] : false);
		$activation_key = (isset($_GET['activation_key']) ? $_GET['activation_key'] : false);

		if($email && $activation_key) {
			$email = strtolower($email);

			// Check if they were already activated
			$find = User::model()->active()->find('LOWER(email) = ?', array($email));

			if(isset($find)) {
			    $this->render(Yii::app()->controller->module->messageUrl[0], array('title' => UserModule::t("User activation"),
								  'content' => UserModule::t("Your account is active.")
								  )
					  );
			} else {
				// Well, they are not already active, so activate them if we can
				$find = User::model()->foractivation()->find('LOWER(email) = ?', array($email));

				if(isset($find) && isset($find->activation_key)) {
					If($find->activateAccount($activation_key, true)) {
						$this->render(Yii::app()->controller->module->messageUrl[0], array('title' => UserModule::t("User activation"),
										     'content' => UserModule::t("Your account has been activated.")
										    )
							      );
					} else {
						$this->render(Yii::app()->controller->module->messageUrl[0], array('title' => UserModule::t("User activation"),
										     'content' => UserModule::t("There was a problem activating you account.")
										    )
							      );
					}
				} else {
					$this->render(Yii::app()->controller->module->messageUrl[0], array('title' => UserModule::t("User activation"),
									     'content' => UserModule::t("Ivalid activation request.")
									     )
						     );
				}
			}
		} else {
			$this->render(Yii::app()->controller->module->messageUrl[0], array('title' => UserModule::t("User activation"),
							     'content' => UserModule::t("Ivalid activation request.")));
		}
	}
}
