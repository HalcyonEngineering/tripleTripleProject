<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

		<?php echo $form->textFieldRow($model,'id',array('class'=>'span5')); ?>

		<?php echo $form->textFieldRow($model,'project_id',array('class'=>'span5')); ?>

		<?php echo $form->textFieldRow($model,'file_name',array('class'=>'span5','maxlength'=>256)); ?>

		<?php echo $form->textFieldRow($model,'file_size',array('class'=>'span5')); ?>

		<?php echo $form->textFieldRow($model,'file_data',array('class'=>'span5')); ?>

	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType' => 'submit',
			'type'=>'primary',
			'label'=>'Search',
		)); ?>
	</div>

<?php $this->endWidget(); ?>
