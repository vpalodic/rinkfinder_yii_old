<?php
    /* @var $this SiteController */
    /* @var $model LoginForm */
    /* @var $form TbActiveForm  */

    $this->pageTitle = Yii::app()->name . ' - Login';
    $this->breadcrumbs = array('Login',);
?>

<?php $this->widget('bootstrap.widgets.TbAlert'); ?>

<h2><?php echo UserModule::t("Login"); ?></h2>

<p><?php echo UserModule::t("Please fill out the following form with your login credentials:"); ?></p>

<div class = "form">
    <?php
        $form = $this->beginWidget('bootstrap.widgets.TbActiveForm',
                                   array('layout' => TbHtml::FORM_LAYOUT_HORIZONTAL,
                                         'id' => 'login-form',
                                         'enableAjaxValidation' => true,
                                         'enableClientValidation' => true,
                                         'clientOptions' => array('validateOnSubmit' => true),
                                         ));
    ?>

    <fieldset>

        <legend><?php echo UserModule::t('Fields with <span class="required">*</span> are required.'); ?></legend>

    	<?php echo $form->errorSummary($model); ?>

        <?php echo $form->textFieldControlGroup($model,
                                                'username',
                                                array('help' => 'Enter a valid username or e-mail address'));
        ?>

        <?php echo $form->passwordFieldControlGroup($model,
                                                    'password');
        ?>

        <?php echo $form->checkBoxControlGroup($model,
                                               'rememberMe');
        ?>

        <?php echo TbHtml::linkButton(UserModule::t("Register"),
                                      array('url' => Yii::app()->getModule('user')->registrationUrl,
                                            'color' => TbHtml::BUTTON_COLOR_LINK,
                                            'inline' => true,
                                           ));
        ?> |
        <?php echo TbHtml::linkButton(UserModule::t("Forgot username or password?"),
                                      array('url' => Yii::app()->getModule('user')->recoveryUrl,
                                            'color' => TbHtml::BUTTON_COLOR_LINK,
                                            'inline' => true,
                                           ));
        ?>

    </fieldset>

    <?php
        echo TbHtml::formActions(array(TbHtml::submitButton('Login',
                                                            array('color' => TbHtml::BUTTON_COLOR_PRIMARY)),
                                       ));
    ?>

    <?php $this->endWidget(); ?>

</div><!-- form -->
