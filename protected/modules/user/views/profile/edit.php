<?php $this->pageTitle=Yii::app()->name . ' - '.UserModule::t("Profile");
$this->breadcrumbs=array(
						 UserModule::t("Profile") => array('profile'),
						 UserModule::t("Edit"),
);
?>
<?php $this->widget('bootstrap.widgets.TbAlert'); ?>

<h2><?php echo UserModule::t('Edit profile'); ?></h2>

<?php echo $this->renderPartial('menu'); ?>

<div class = "form">
    <?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm',
                    array(
                          'layout' => TbHtml::FORM_LAYOUT_HORIZONTAL,
                          'id' => 'profile-form',
						  'htmlOptions' => array('enctype'=>'multipart/form-data'),
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
			echo $form->errorSummary(array($model, $profile));
		?>

        <?php
			echo $form->textFieldControlGroup($model,
											  'username');
        ?>

        <?php
			echo $form->emailFieldControlGroup($model,
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

    </fieldset>

    <?php
		echo TbHtml::formActions(array(TbHtml::submitButton($model->isNewRecord ?
															UserModule::t('Create') :
															UserModule::t('Save'),
															array('color' => TbHtml::BUTTON_COLOR_PRIMARY)),
									   ));
    ?>

    <?php $this->endWidget(); ?>

</div><!-- form -->
