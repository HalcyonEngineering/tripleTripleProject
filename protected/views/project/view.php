<?php
/**
 * @var $model Project
 */
$this->breadcrumbs=array(
	                   $model->name
                   );
?>
<div class="span-9 pull-right" ><!--Buttons-->
<div class="span-3" style="padding:5px;" >
<?php $this->widget('ModalOpenButton',
                    array(
                      'button_id'=>'list-files-btn',
                      'url' => Yii::app()->createUrl("fileDoc/listFiles",array("project_id"=>$model->id)),
                      'label' => 'View files in project',
                      'type' => 'common',
                    ));
?>
</div>
<div class="span-3" style="padding:5px;" >
<?php $this->widget('ModalOpenButton',
                    array(
                      'button_id'=>'list-files-btn',
                      'url' => Yii::app()->createUrl("fileDoc/create",array("project_id"=>$model->id)),
                      'label' => 'Add file to project',
                      'type' => 'common',
                    ));
?>
</div>
</div><!--End of Buttons-->

<h1> <?php echo $model->name; ?></h1>

<p><?php echo $model->desc; ?> </p>

<p> <?php $target = $model->target; if ($target!=null)echo "Target Date: ".$model->target; ?> </p>

<h3> Volunteers and Roles</h3>


 <?php if(count($emptyRolesProvider->getData()) !== 0){
	 echo "You have not assigned a volunteer for these roles.";
	 $this->widget('bootstrap.widgets.TbGridView', array(
		 'id'=>'unassigned-role-grid',
		 'dataProvider' => $emptyRolesProvider,
		 'template'=>'{items}',
	     'columns' => array(
		     'name:text:Role Name',
	         array(
		         'class'=>'bootstrap.widgets.TbButtonColumn',
	             'template'=>'{view}',
	             'viewButtonUrl'=>'Yii::app()->controller->createUrl("/role/view",array("id"=>$data->primaryKey))',
	         ),
	     ),
	 ));
 } ?>



<?php $this->widget('bootstrap.widgets.TbGridView', array(
	'dataProvider' => $roleDataProvider,
	'type'=>'bordered hover',
	'columns' => array(
//		array('class'=>'bootstrap.widgets.TbDataColumn',
//		      'name'=>'Role Name',
//		      'type'=>'text',
//		      'value'=>'$data->role->name',
//		      'sortable'=>true,
//		),
		'role.name',
		array('class'=>'bootstrap.widgets.TbButtonColumn',
		      'template'=>'{view} {delete}',
		      'viewButtonUrl'=>'Yii::app()->controller->createUrl("/role/view",array("id"=>$data->role->primaryKey))',
		      'deleteButtonUrl'=>'Yii::app()->controller->createUrl("/role/delete",array("id"=>$data->role->primaryKey))',
		      'buttons'=>array(
			      'view'=>array('label'=>'View role details'),
			      'delete'=>array('label'=>'Delete role'),
		      ),
		),
	    'user.name',
//		array('class'=>'bootstrap.widgets.TbDataColumn',
//		      'name'=>'Volunteer Name',
//		      'type'=>'text',
//		      'value'=>'(isset($data->user)) ? $data->user->name : "No User Assigned"',
//		),
		array(
                    'class'=>'bootstrap.widgets.TbButtonColumn',
                    'template'=>'{remove}',
                    'buttons'=> array(
                        'remove'=> array(
                            'icon'=>'fire',
                            'label'=>'Unassign volunteer from role',
                            'url'=>'Yii::app()->controller->createUrl("/volunteer/removeFromRole",
                                array("volunteer_id"=>$data->user->primaryKey, "role_id"=>$data->role->primaryKey))',
                            'htmlOptions' => array('confirm' => 'Download file?', 'target'=>'_blank'),
                        )
                    )
                ),
		//array('class'=>'bootstrap.widgets.TbButtonColumn',
                //      'template'=>'{delete}',
		//      'deleteButtonUrl'=>'Yii::app()->controller->createUrl("/volunteer/removeFromRole",
                //        array("volunteer_id"=>$data->user->primaryKey, "role_id"=>$data->role->primaryKey)
                //)',),
	),

)); ?>
<div class="span-3" style="padding:5px;" >
<?php $this->widget('ModalOpenButton',

                    array(
                          'button_id'=>'add-role-btn',
                          'url' => Yii::app()->createUrl("role/create?project_id=$model->id"),
                          'label' => 'Add Role',
                          'type' => 'common',
                          ));
    ?>
</div>
