<?php

class DefaultController extends Controller
{
	
	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return CMap::mergeArray(parent::filters(), array(
			'accessControl', // perform access control for CRUD operations
		));
	}
	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',  // allow all admins to perform 'index', 'view' actions
				'actions' => array('index', 'view'),
				'users' => UserModule::getAdmins(),
			),
			array('allow',  // allow all users to perform 'login' action
				'actions' => array('login'),
				'users' => array('*'),
			),
			array('deny',  // deny all users
				'users' => array('*'),
			),
		);
	}	

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('User', array(
				'criteria'=>array(
					'condition'=>'status > ' . User::STATUS_BANNED . ' AND status <= ' . User::STATUS_ACTIVE,
					),
				'pagination'=>array(
					'pageSize'=>Yii::app()->controller->module->user_page_size,
					),
				)
			);

		$this->render('/user/index', array(
				'dataProvider' => $dataProvider,
				)
			);
	}
}