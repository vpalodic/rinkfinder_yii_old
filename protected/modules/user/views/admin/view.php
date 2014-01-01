<?php
	$this->breadcrumbs = array(
							   UserModule::t('Users') => array('admin'),
							   $model->username,
							   );
?>

<h2><?php echo UserModule::t('View User') . ' "' . $model->username . '"'; ?></h2>

<?php
	echo $this->renderPartial('_menu',
								array(
									  'list' => array(
													  CHtml::link(UserModule::t('Create User'),
																  array('create')),
													  CHtml::link(UserModule::t('Update User'),
																  array('update',
																		'id' => $model->id)),
													  CHtml::linkButton(UserModule::t('Delete User'),
																		array('submit' => array('delete',
																								'id' => $model->id),
																			  'confirm' => UserModule::t('Are you sure to delete this item?'))),
													  ),
									  ));

	$attributes = array(
		'id',
		'username',
	);

	$profileFields = ProfileField::model()->forOwner()->sort()->findAll();

	if($profileFields) {
		foreach($profileFields as $field) {
			array_push($attributes, array(
					'label' => UserModule::t($field->title),
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
		'email',
		array(
			'name' => 'superuser',
			'value' => User::itemAlias("AdminStatus", $model->superuser),
		),
		array(
			'name' => 'status',
			'value' => User::itemAlias("UserStatus", $model->status),
		),
		'password',
		'activation_key',
		array(
			'name' => 'last_visit',
			'value' => (($model->last_visit != '0000-00-00 00:00:00') ?
				    date_format(date_create_from_format('Y-m-d H:i:s', $model->last_visit), 'm-d-Y H:i:s') :
				    UserModule::t('Not visited')),
		),
		array(
			'name' => 'created_on',
			'value' => date_format(date_create_from_format('Y-m-d H:i:s', $model->created_on), 'm-d-Y H:i:s'),
		),
		array(
			'name' => 'createdBy',
			'value' => $model->createdBy->username,
		),
		array(
			'name' => 'updated_on',
			'value' => date_format(date_create_from_format('Y-m-d H:i:s', $model->created_on), 'm-d-Y H:i:s'),
		),
		array(
			'name' => 'updatedBy',
			'value' => $model->createdBy->username,
		)
	);

	$this->widget('yiiwheels.widgets.detail.WhDetailView', array(
		'data' => $model,
		'attributes' => $attributes,
	));


?>
