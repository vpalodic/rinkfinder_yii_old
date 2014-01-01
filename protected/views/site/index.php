<?php
    /* @var $this SiteController */
    $this->pageTitle = Yii::app()->name;
	$this->breadcrumbs=array(UserModule::t(""));
?>

<?php $this->widget('bootstrap.widgets.TbAlert'); ?>

<h2>Welcome to <i><?php echo CHtml::encode(Yii::app()->name); ?></i></h2>

<p>Congratulations! You have successfully created your Yii application.</p>

<p>You may change the content of this page by modifying the following two files:</p>
<ul>
	<li>View file: <?php echo TbHtml::code(__FILE__); ?></li>
	<li>Layout file: <?php echo TbHtml::code($this->getLayoutFile('main')); ?></li>
</ul>

<p>For more details on how to further develop this application, please read
the <a href="http://www.yiiframework.com/doc/">documentation</a>.
Feel free to ask in the <a href="http://www.yiiframework.com/forum/">forum</a>,
should you have any questions.</p>
