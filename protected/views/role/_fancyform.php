<div class="data-display">
	<h1><?php echo $model->name;
			if (Yii::app()->user->isManager()){
				$this->widget('bootstrap.widgets.TbButton',
				              array('buttonType' => 'link',
				                    'type' => 'link', // the chrome of the button
				                    'label' => 'edit', // text of the button
				                    'htmlOptions' => array('onclick'=>"$('#role-onboarding-form').show();$('.data-display').hide();"),
				              )
				);
			}
		?></h1>
	<?php if(Yii::app()->user->isManager()) echo "<i>This is what your volunteers will see: </i>";?><br /><br />
	<i><?php echo $model->desc; ?></i><br /><br />
	<?php if(Yii::app()->user->isManager()) echo "<b>Welcome message: </b>";?><?php echo $onboardingModel->onboarding_welcome; ?><br /><br />
	<b>Instructions: </b><?php echo $onboardingModel->onboarding_instructions; ?><br /><br />
	<b>Contact information: </b><?php echo $onboardingModel->onboarding_contact; ?><br /><br />
</div>

<?php
	if(Yii::app()->user->isManager()){
		$this->renderPartial('/role/_form',
		                     array('model'=>$model,
		                           'onboardingModel'=>$onboardingModel,
		                           'embeddedForm'=>true));
	}
?>
