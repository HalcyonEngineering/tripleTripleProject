<?php

$messagesIcon= CHtml::image(Yii::app()->getBaseUrl().'/images/messages.png' , 'messages', array('class'=>'img-circle'));
    
//@TODO Add settings on conditions of displayed notificatons

$this->widget(
     'bootstrap.widgets.TbNavbar',
     array(
	     'type' => 'inverse',
	     'brand' => CHtml::image(Yii::app()->getBaseUrl().'/images/Pitchn-Logo-Mark-317x150.png', "Pitch'n", array("width"=>"140px", "height"=>"100px")),
	     'brandOptions' =>  array('style' => 'margin-left: -15px;'),
	     'brandUrl' => Yii::app()->homeUrl,
	     'collapse' => false, // requires bootstrap-responsive.css
	     'fixed' => 'top',
	     'items' => array(
		     array(
			     'class' => 'bootstrap.widgets.TbMenu',
			     'encodeLabel' => false,
			     'htmlOptions' => array('class' => 'pull-right'),
			     'items' => array(
				     // This is the messages Drop Down Menu
				     array('label' => $messagesIcon,
				           'url' => '#',
				           'items' => array(
					           array('label' => 'Inbox', 'url' => array('site/page', 'view'=>'messages')),   //THIS NEWS TO SHOW NUMBER OF UNREAD MESSAGES
					           array('label' => 'Send Email', 'url' => array('mail/contact')),
				           ),
				           'visible'=>!Yii::app()->user->isGuest,
				     ),//End Messages drop down.

				     '---', //Divider

				     // This is the User Drop Down Menu
				     array(
					     'label' => Yii::app()->user->name,
					     'url' => '#',
					     'items' => array(
						     array('label' => 'My Profile', 'url' => array('account/profile')),
						     array('label'=>'Take a Tour', 'url'=>'#'),
						     array('label'=>'FAQ', 'url'=>'#'),
						     array('label'=>'Settings', 'url'=>array('account/settings')),
						     array('label' => 'Signout', 'url' => array('account/logout')),
					     ),
					     'visible'=>!Yii::app()->user->isGuest,
				     ),//End user drop down.

				     array('label'=>'Login',
				           'url'=>array('account/login'),
				           'visible'=>Yii::app()->user->isGuest),//End user login
			     ),//End menu items.
		     ),//End TbMenu
               array('class' => 'notification_TbDropdown',
                 'htmlOptions' => array('class' =>'pull-right', 'id' =>'notification-button'),
		         'visible'=>!Yii::app()->user->isGuest
               ) //End notification dropdown
	     ),//End navbar items.
     )//End navbar
);//End widget instantiation


?>
