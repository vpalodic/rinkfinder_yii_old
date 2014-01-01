<?php
    $this->pageTitle=Yii::app()->name . ' - ' . UserModule::t("Messages");
	$this->breadcrumbs=array(UserModule::t("Messages"));
?>

<?php $this->widget('bootstrap.widgets.TbAlert'); ?>

<h2><?php echo UserModule::t("Messages"); ?></h2>

<div class="form">
<?php echo $content; ?>

</div><!-- yiiForm -->
