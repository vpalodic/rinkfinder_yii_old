<?php

class RecoveryController extends Controller
{
	public $defaultAction = 'recovery';
	
	/**
	 * Recovery password
	 */
	public function actionRecovery () {
		$form = new UserRecoveryForm;
		if (Yii::app()->user->id) {
		    	$this->redirect(Yii::app()->controller->module->returnUrl);
		} else {
			$email = ((isset($_GET['email'])) ? $_GET['email'] : '');
			$email = strtolower($email);
			$activation_key = ((isset($_GET['activation_key'])) ? $_GET['activation_key'] : '');
			
			if($email && $activation_key) {
				$form2 = new UserChangePassword;
		    		$find = User::model()->all()->find('LOWER(email) = ?', array($email));;
		    		
				if(isset($find) && $find->activation_key == $activation_key) {
			    		if(isset($_POST['UserChangePassword'])) {
						$form2->attributes = $_POST['UserChangePassword'];
						if($form2->validate()) {
							$find->activate($activation_key);
							
							
							if($find->changePassword($form2->password)) {
								Yii::app()->user->setFlash('recoveryMessage',
											   UserModule::t("New password has been saved."));
							} else {
								Yii::app()->user->setFlash('recoveryMessage',
											   UserModule::t("Unable to save new password."));
							}
							
							$this->redirect(Yii::app()->controller->module->recoveryUrl);
						}
					}
					$this->render('changepassword', array('form' => $form2));
		    		} else {
		    			Yii::app()->user->setFlash('recoveryMessage',
								   UserModule::t("Ivalid recovery link."));
					$this->redirect(Yii::app()->controller->module->recoveryUrl);
		    		}
		    	} else {
			    	if(isset($_POST['UserRecoveryForm'])) {
			    		$form->attributes = $_POST['UserRecoveryForm'];
			    		if($form->validate()) {
			    			$user = User::model()->all()->findbyPk($form->user_id);
//						$activation_url = 'http://' . $_SERVER['HTTP_HOST'] . $this->createUrl(implode(Yii::app()->controller->module->recoveryUrl),
//														       array("activation_key" => $user->activation_key,
//															     "email" => $user->email));
						$activation_url = $this->createAbsoluteUrl(Yii::app()->controller->module->recoveryUrl[0],
											   array("activation_key" => $user->activation_key,
												 "email" => $user->email));
						$subject = UserModule::t("{site_name} account recovery request",
									 array(
									       '{site_name}'=>Yii::app()->name,
									       ));
			    			$message = UserModule::t("{site_name} account recovery request. To recover your account go to {activation_url}",
			    					array(
			    						'{site_name}' => Yii::app()->name,
			    						'{activation_url}' => $activation_url,
			    					));
						$mailsent = UserModule::sendMail($user->email, $subject, $message);
						
			    			If($mailsent != true) {
							Yii::app()->user->setFlash('recoveryMessage',
							   UserModule::t("Error sending recovery e-mail: " . $mailsent));
						} else {
							Yii::app()->user->setFlash('recoveryMessage',
							   UserModule::t("Instructions on recoverying your account have been e-mailed to you."));
						}
			    			$this->refresh();
			    		}
			    	}
		    		$this->render('recovery',array('form'=>$form));
		    	}
		}
	}
}