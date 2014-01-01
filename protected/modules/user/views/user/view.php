<?php
	$this->breadcrumbs = array(UserModule::t('Users') => array('index'),
							   $model->username,
							   );
?>

<?php $this->widget('bootstrap.widgets.TbAlert'); ?>

<h2><?php echo UserModule::t('View User') . ' "' . $model->username . '"'; ?></h2>

<?php
	if(UserModule::isAdmin()) {
?>
	<ul class="actions">
		<li><?php echo TbHtml::link(UserModule::t('Create User'),array('/user/admin/create')); ?></li>
		<li><?php echo TbHtml::link(UserModule::t('List Users'),array('/user')); ?></li>
		<li><?php echo TbHtml::link(UserModule::t('Manage Users'),array('/user/admin')); ?></li>
		<li><?php echo TbHtml::link(UserModule::t('Manage Profile Fields'),array('profileField/admin')); ?></li>
	</ul><!-- actions -->
<?php
	}
?>

<?php

// For all users
	$attributes = array(
			'username',
			);

	$profileFields = ProfileField::model()->forAll()->sort()->findAll();

	if($profileFields) {
		foreach($profileFields as $field) {
			array_push($attributes,
					   array('label' => UserModule::t($field->title),
							 'name' => $field->varname,
							 'type' => 'raw',
							 'value' => (($field->widgetView($model->profile)) ?
										 $field->widgetView($model->profile) :
										 (($field->range) ?
										  Profile::range($field->range, $model->profile->getAttribute($field->varname)) :
										  $model->profile->getAttribute($field->varname))),
							 ));
		}
	}

	array_push($attributes,
			   array('name' => 'last_visit',
					 'value' => (($model->last_visit != '0000-00-00 00:00:00') ?
								 date_format(date_create_from_format('Y-m-d H:i:s', $model->last_visit), 'm-d-Y H:i:s') :
								 UserModule::t('Not visited')),
					 ),
			   array('name' => 'created_on',
					 'value' => date_format(date_create_from_format('Y-m-d H:i:s', $model->created_on), 'm-d-Y H:i:s'),
					 )
			   );

	$this->widget('yiiwheels.widgets.detail.WhDetailView',
				  array('data' => $model,
						'attributes' => $attributes,
						));

?>
