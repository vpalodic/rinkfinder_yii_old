<ul class="actions">
<?php 
	if (count($list)) {
		foreach ($list as $item)
			echo "<li>".$item."</li>";
	}
?>
	<li><?php echo CHtml::link(UserModule::t('List Users'),array('/user')); ?></li>
	<li><?php echo CHtml::link(UserModule::t('Manage Users'),array('admin')); ?></li>
	<li><?php echo CHtml::link(UserModule::t('Manage Profile Fields'),array('profileField/admin')); ?></li>
</ul><!-- actions -->