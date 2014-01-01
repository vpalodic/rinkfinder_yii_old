<?php
	$this->pageTitle=Yii::app()->name . ' - ' . UserModule::t("Change Password");
	$this->breadcrumbs=array(
							 UserModule::t("Profile") => array('/user/profile'),
							 UserModule::t("Change Password"),
							 );
?>

<?php $this->widget('bootstrap.widgets.TbAlert'); ?>

<h2><?php echo UserModule::t("Change password"); ?></h2>

<?php echo $this->renderPartial('menu'); ?>

<div class = "form">
    <?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm',
                    array(
                          'layout' => TbHtml::FORM_LAYOUT_HORIZONTAL,
                          'id' => 'changepassword-form',
						  'enableAjaxValidation' => true,
                          'enableClientValidation' => true,
                          'clientOptions' => array(
                                                   'validateOnSubmit' => true,
                                                   ),
                          ));
    ?>

    <fieldset>
        <legend><?php echo UserModule::t('Fields with <span class="required">*</span> are required.'); ?></legend>

    	<?php
			echo $form->errorSummary($model);
		?>

        <?php
			echo $form->passwordFieldControlGroup($model,
												  'password');
        ?>

        <?php
			echo $form->passwordFieldControlGroup($model,
												  'verifyPassword');
        ?>

    </fieldset>

    <?php
		echo TbHtml::formActions(array(TbHtml::submitButton('Save',
															array('color' => TbHtml::BUTTON_COLOR_PRIMARY)),
									   ));
    ?>

    <?php $this->endWidget(); ?>

</div><!-- form -->
