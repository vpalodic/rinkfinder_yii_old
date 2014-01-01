<?php
		$this->breadcrumbs = array(
								   UserModule::t("Users"),
								   );
?>

<?php $this->widget('bootstrap.widgets.TbAlert'); ?>

<h2><?php echo UserModule::t("List Users"); ?></h2>

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
		$this->widget('yiiwheels.widgets.grid.WhGridView',
					  array('filter' => null,
							'responsiveTable' => true,
							'fixedHeader' => true,
							'headerOffset' => 40,
							'type' => 'striped bordered',
							'template' => "{items}\n{pager}",
							'dataProvider' => $dataProvider,
							'columns' => array(array('name' => 'username',
													 'type'=>'raw',
													 'value' => 'TbHtml::link(CHtml::encode($data->username),
																			  array("user/view",
																				    "id" => $data->id))',
													 ),
											   array('name' => 'last_visit',
													 'value' => '(($data->last_visit != "0000-00-00 00:00:00") ?
																 date_format(date_create_from_format("Y-m-d H:i:s", $data->last_visit), "m-d-Y H:i:s") :
																 UserModule::t("Not visited"))',
													 ),
											   array('name' => 'created_on',
													 'value' => 'date_format(date_create_from_format("Y-m-d H:i:s", $data->created_on), "m-d-Y H:i:s")',
													 ),
											   ),
							)
					  );
?>
