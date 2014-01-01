<?php
$this->breadcrumbs=array(
	$this->module->id,
);

    foreach(Yii::app()->user->getFlashes() as $key => $message) {
        echo '<div class="flash-' . $key . '">' . $message . "</div>\n";
    }


?>

<? if(!empty($not_imported))
{?>

    <h3>The following rows were not imported:</h3>
    <? echo CHtml::textArea('csvimport',$not_imported,array('submit'=>'','cols'=>'60','rows'=>'10')); ?>
    <br />
    <br />
    <br />
<? }
 ?>




<h1>Import Users</h1>

<p>Use the form below to import a CSV formatted list of users.</p>

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'import-form',
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
)); ?>

	<div class="row">

            <? echo CHtml::textArea('csvimport',null,array('submit'=>'','cols'=>'60','rows'=>'10')); ?>

	<div class="row buttons">
            <?php echo CHtml::submitButton('Submit',array('class'=>'button')); ?>
	</div>

<?php $this->endWidget(); ?>
