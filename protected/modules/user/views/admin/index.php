<?php
$this->breadcrumbs=array(
						 UserModule::t('Users') => array('/user'),
						 UserModule::t('Manage'),
);
?>
<h1><?php echo UserModule::t("Manage Users"); ?></h1>

<?php echo $this->renderPartial('_menu', array(
		'list'=> array(
			CHtml::link(UserModule::t('Create User'), array('create')),
		),
	));
?>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'dataProvider'=>$dataProvider,
	'columns'=>array(
		array(
			'name' => 'id',
			'type'=>'raw',
			'value' => 'CHtml::link(CHtml::encode($data->id),array("admin/update","id"=>$data->id))',
		),
		array(
			'name' => 'username',
			'type'=>'raw',
			'value' => 'CHtml::link(CHtml::encode($data->username),array("admin/view","id"=>$data->id))',
		),
		array(
			'name'=>'email',
			'type'=>'raw',
			'value'=>'CHtml::link(CHtml::encode($data->email), "mailto:".$data->email)',
		),
		array(
			'name' => 'created_on',
			'value' => 'date_format(date_create($data->created_on), "m-d-Y H:i:s")',
		),
		array(
			'name' => 'last_visit',
			'value' => '(($data->last_visit != "0000-00-00 00:00:00") ?
							date_format(date_create($data->last_visit), "m-d-Y H:i:s") :
							UserModule::t("Not visited"))',
		),
		array(
			'name'=>'status',
			'value'=>'User::itemAlias("UserStatus", $data->status)',
		),
		array(
			'name'=>'superuser',
			'value'=>'User::itemAlias("AdminStatus", $data->superuser)',
		),
		array(
			'class' => 'CButtonColumn',
		),
	),
)); ?>
