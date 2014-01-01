<?php
	$this->pageTitle = Yii::app()->name . ' - ' . UserModule::t("Profile");
	$this->breadcrumbs = array(UserModule::t("Profile"));
?>

<?php $this->widget('bootstrap.widgets.TbAlert'); ?>

<h2><?php echo UserModule::t('Your profile'); ?></h2>

<?php echo $this->renderPartial('menu'); ?>

<?php
	$attributes = array(
						'username',
						);

	$profileFields = ProfileField::model()->forOwner()->sort()->findAll();

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
		'email',
		array(
			'name' => 'status',
			'value' => User::itemAlias("UserStatus", $model->status),
		),
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
			'value' => date_format(date_create_from_format('Y-m-d H:i:s', $model->updated_on), 'm-d-Y H:i:s'),
		),
		array(
			'name' => 'updatedBy',
			'value' => $model->updatedBy->username,
		)
	);

	$this->widget('yiiwheels.widgets.detail.WhDetailView',
				  array('data' => $model,
						'attributes' => $attributes,
						));

?>
