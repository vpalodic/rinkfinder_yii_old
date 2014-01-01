<?php
	$this->pageTitle = Yii::app()->name . ' - ' . UserModule::t("Registration");
	$this->breadcrumbs=array(UserModule::t("Registration"));
?>

<?php $this->widget('bootstrap.widgets.TbAlert'); ?>

<h2><?php echo UserModule::t("Registration"); ?></h2>

<div class = "form">
	<?php
		$form = $this->beginWidget('bootstrap.widgets.TbActiveForm',
                                   array('layout' => TbHtml::FORM_LAYOUT_HORIZONTAL,
                                         'id' => 'registration-form',
										 'enableAjaxValidation' => true,
                                         'enableClientValidation' => true,
                                         'clientOptions' => array('validateOnSubmit' => true),
										 'htmlOptions' => array('enctype' => 'multipart/form-data'),
										 ));
	?>

    <fieldset>
        <legend><?php echo UserModule::t('Fields with <span class="required">*</span> are required.'); ?></legend>

    	<?php echo $form->errorSummary($model); ?>

        <?php echo $form->textFieldControlGroup($model,
                                                'username');
        ?>

        <?php echo $form->passwordFieldControlGroup($model,
													'password');
        ?>

        <?php echo $form->passwordFieldControlGroup($model,
													'verifyPassword');
        ?>

        <?php echo $form->emailFieldControlGroup($model,
                                                'email');
        ?>

		<?php
			$profileFields = $profile->getFields();

			if($profileFields) {
				foreach($profileFields as $field) {

					if($field->widgetEdit($profile)) {
						echo $form->labelEx($profile, $field->varname);
						echo $field->widgetEdit($profile);
						echo $form->error($profile, $field->varname);
					} elseif($field->range) {
						echo $form->dropDownListControlGroup($profile,
															 $field->varname,
															 Profile::range($field->range));
					} elseif($field->field_type=="TEXT") {
						echo $form->textAreaControlGroup($profile,
														 $field->varname,
														 array('rows' => 6,
															   'cols' => 50));
					} else {
						echo $form->textFieldControlGroup($profile,
														  $field->varname,
														  array('size' => 60,
																'maxlength' => (($field->field_size) ? $field->field_size : 255)));
					}
				}
			}
		?>

	<?php if(Yii::app()->doCaptcha('registration')): ?>
		<div class="controls">
				<?php $this->widget('CCaptcha'); ?>
		</div>

		<?php echo $form->textFieldControlGroup($model,
                                                'verifyCode');
        ?>

		<div class="control-group">
			<p class="hint"><?php echo UserModule::t("Please enter the letters as they are shown in the image above."); ?>
			<br/><?php echo UserModule::t("Letters are not case-sensitive."); ?></p>
		</div>
	<?php endif; ?>

    </fieldset>

    <?php
		echo TbHtml::formActions(array(TbHtml::submitButton('Register',
															array('color' => TbHtml::BUTTON_COLOR_PRIMARY)),
									   ));
    ?>

    <?php $this->endWidget(); ?>
</div><!-- form -->
