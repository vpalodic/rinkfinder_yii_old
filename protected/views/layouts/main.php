<?php /* @var $this Controller */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="en" />

	<!-- blueprint CSS framework -->
<!--	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/screen.css" media="screen, projection" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/print.css" media="print" />
-->	<!--[if lt IE 8]>
<!--	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ie.css" media="screen, projection" /> -->
	<![endif]-->
<!--
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/main.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/form.css" />
-->
	<?php Yii::app()->bootstrap->register(); ?>

	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
</head>

<body>

<div class="container" id="page">

	<div id="mainmenu">
		<?php $this->widget('bootstrap.widgets.TbNavbar', array(
				'brandLabel' => Yii::app()->name,
				'display' => TbHtml::NAVBAR_DISPLAY_FIXEDTOP, // default is static to top
				'collapse' => true,
				'fluid' => true,
				'color' => TbHtml::NAVBAR_COLOR_INVERSE,

				'items' => array(
						array(
							  'class' => 'bootstrap.widgets.TbNav',
							  'items' => array(
												array('label' => 'Home', 'url' => array('/site/index')),
												array('label' => 'About', 'url' => array('/site/page', 'view' => 'about')),
												array('label' => 'Contact', 'url' => array('/site/contact')),
											),
								),
						array(
							  'class' => 'bootstrap.widgets.TbNav',
							  'htmlOptions'=>array('class'=>'pull-right'),
							  'items' => array(
											   array('label'=> Yii::app()->getModule('user')->t("Login"),
													 'url' => Yii::app()->getModule('user')->loginUrl,
													 'visible'=> Yii::app()->user->isGuest),
											   array('label'=> Yii::app()->getModule('user')->t("Register"),
													 'url' => Yii::app()->getModule('user')->registrationUrl,
													 'visible'=> Yii::app()->user->isGuest),
											   array('label'=> Yii::app()->getModule('user')->t(Yii::app()->user->name),
												//	 'url' => Yii::app()->getModule('user')->profileUrl,
													 'visible'=> !Yii::app()->user->isGuest,
													 'items' => array(array('label' => 'Profile'),
																	  array('label' => 'View Profile',
																			'url' => Yii::app()->getModule('user')->profileUrl),
																	  array('label' => 'Edit Profile',
																			'url' => Yii::app()->getModule('user')->editProfileUrl),
																	  array('label' => 'Change Password',
																			'url' => Yii::app()->getModule('user')->changePasswordUrl),
																	  TbHtml::menuDivider(),
																	  array('label' => 'Logout',
																			'url' => Yii::app()->getModule('user')->logoutUrl)
																	 )),
											   array('label'=> Yii::app()->getModule('user')->t("Super Admin Operations"),
												//	 'url' => array('/user/admin'),
													 'visible'=>Yii::app()->getModule('user')->isAdmin(),
													 'items' => array(array('label' => 'User Options'),
																	  array('label' => 'Create New User',
																			'url' => Yii::app()->getModule('user')->createUserUrl),
																	  array('label' => 'List Users',
																			'url' => Yii::app()->getModule('user')->listUsersUrl),
																	  array('label' => 'Manage Users',
																			'url' => Yii::app()->getModule('user')->manageUsersUrl),
																	  TbHtml::menuDivider(),
																	  array('label' => 'Profile Field Options'),
																	  array('label' => 'Create New Profile Field',
																			'url' => Yii::app()->getModule('user')->createProfileFieldUrl),
																	  array('label' => 'Manage Profile Fields',
																			'url' => Yii::app()->getModule('user')->manageProfileFieldsUrl),
																	  TbHtml::menuDivider(),
																	  array('label' => 'Auth & Role Options'),
																	  array('label' => 'Assignments',
																			'url' => array('/auth/assignment/index')),
																	  array('label' => 'Roles',
																			'url' => array('/auth/role/index')),
																	  array('label' => 'Tasks',
																			'url' => array('/auth/task/index')),
																	  array('label' => 'Operations',
																			'url' => array('/auth/operation/index')),
																	  )),
										),
								),
						),
		)); ?>
	</div><!-- mainmenu -->
	<div id="header">
		<div id="logo"><?php //echo CHtml::encode(Yii::app()->name); ?></div>
	</div><!-- header -->

	<?php if(isset($this->breadcrumbs)):?>
		<?php $this->widget('bootstrap.widgets.TbBreadcrumb', array(
			'links' => $this->breadcrumbs,
		)); ?><!-- breadcrumbs -->
	<?php endif?>

	<?php echo $content; ?>

	<div class="clear">

	</div>

	<footer class = "footer" id="footer">
		<div class = "container">
				Copyright &copy; <?php echo date('Y'); ?> by MIAMA.<br/>
				All Rights Reserved.<br/>
		</div><!-- footer -->
	</footer>

</div><!-- page -->

</body>
</html>
