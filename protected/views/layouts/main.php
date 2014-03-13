<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="en" />



	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
<!-- blueprint CSS framework -->
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/screen.css" media="screen, projection" />
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/print.css" media="print" />
<!--[if lt IE 8]>
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ie.css" media="screen, projection" />
<![endif]-->

    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/main.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/form.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/header.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/mainmenu.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/pvmsmain.css" />
    <link rel="shortcut icon" href="<?php echo Yii::app()->request->baseUrl; ?>/images/favicon2.ico" type="image/x-icon" />
</head>

<body>

<div class="container" id="page">
<div id="header">
<?php include('header.php');?>
</div><!-- header -->
<div id="mainmenu">
<?php
	if (Yii::app()->user->isAdmin()) {
		include('admin.php');
	} elseif (Yii::app()->user->isManager()) {
		include('manager.php');
	} elseif (Yii::app()->user->isVolunteer()) {
		include('volunteer.php');
	}
    ?>
</div><!-- mainmenu -->

<div id="menu-background" class="span-24 last">
<div id="inside-page" class="span-22 pull-right last">
	<?php $this->beginWidget('bootstrap.widgets.TbModal', array(
		'id'=>'modal',
		'options'=>array(
			'autoOpen'=>false, //important!
		),
	));
	?>
	<div id="modal-body" class="modal-body"></div>

	<?php $this->endWidget();?>

	<?php echo $content; ?>


	<div id="footer">
		Copyright &copy; <?php echo date('Y'); ?> by Halcyon Engineering<br/>
		All Rights Reserved.<br/>
		<?php echo Yii::powered(); ?>
	</div><!-- footer -->
</div>
</div><!-- content -->

</div><!-- page -->

</body>
</html>
