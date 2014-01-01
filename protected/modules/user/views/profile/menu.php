<ul class="actions">
<?php 
if(UserModule::isAdmin()) {
?>
        <li><?php echo CHtml::link(UserModule::t('Create User'),array('/user/admin/create')); ?></li>
	<li><?php echo CHtml::link(UserModule::t('List Users'),array('/user')); ?></li>
        <li><?php echo CHtml::link(UserModule::t('Manage Users'),array('/user/admin')); ?></li>
	<li><?php echo CHtml::link(UserModule::t('Manage Profile Fields'),array('profileField/admin')); ?></li>
<?php 
} else {
?>
<?php
}
?>
<li><?php echo CHtml::link(UserModule::t('Profile'), array('/user/profile')); ?></li>
<li><?php echo CHtml::link(UserModule::t('Edit'), array('edit')); ?></li>
<li><?php echo CHtml::link(UserModule::t('Change password'), array('changepassword')); ?></li>
<li><?php echo CHtml::link(UserModule::t('Logout'), array('/user/logout')); ?></li>
</ul>