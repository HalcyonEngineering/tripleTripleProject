<?php

class FileDocController extends Controller
{
	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',
				'actions'=>array('download', 'listFiles'),
			    'users'=>array('@'),
			),
			array('allow', // allow managers to create update and delete.
				'actions'=>array('create','update','delete'),
				'expression'=>'Yii::app()->user->isManager()'
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}


	/**
 	 * Creates a new model.
 	 * If creation is successful, the browser will be redirected to the 'view' page.
 	 */
	public function actionCreate($project_id)
	{
		$projectModel = Project::model()->findByPk($project_id);
        if(Yii::app()->user->isManagerForOrg($projectModel->org_id)){
		$model=new FileDoc; // remember: this is a local var
		$model->project_id = $project_id;
        if(isset($_POST['FileDoc']))
		{
            $model->attributes=$_POST['FileDoc'];
            Yii::log('FileDoc uploadedfile before set from cuploadedfile getinstance: '.$model->uploadedfile, 'warning', 'FileDoc');
            $model->uploadedfile=CUploadedFile::getInstance($model,'uploadedfile');
            if($model->save())
            {
                $project = Project::model()->findByAttributes(array('id' => $model->project_id,));
	            $roleList = $project->roles;
	            $userList = array();
                foreach ($roleList as $role){
	                $userList = CMap::mergeArray($userList, $role->users);
                }
	            Notification::notifyAll($userList,
	                                    $model->file_name . " added to " . $project->name . " project files. Click here for download."  ,
	                                    $this->createUrl('/FileDoc/download', array('id' => $model->id)));
	            $this->redirect(array('/project/view','id'=>$project_id)); // redirect to success page
            }
        }

		$this->renderModal('create',array(
			'model'=>$model,
		));
        } else {
	        throw new CHttpException(403, "You can not add a file to this project.");
        }
	}

	public function actionDownload($id)
	{
		if($this->getCurUser()->hasProjectAccess($id)){
            $model = FileDoc::model()->findByPk($id);

		// note: documentation on sendFile: http://www.yiiframework.com/doc/api/1.1/CHttpRequest#sendFile-detail
		Yii::app()->getRequest()->sendFile($model->file_name,$model->file_data); //TODO: put actual useful file data here //getRequest returns the request component of Yii
		
		//Yii::log('download: after workhorse code', 'warning', 'FileDoc'); //NOTE: it seems like nothing after the sendFile gets reached due to terminate (?)
		} else {
			throw new CHttpException(403, "You do not have the permissions to download a file from this project.");
		}
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
		if(Yii::app()->user->isManagerForOrg($model->project->org_id)){
			if(isset($_POST['FileDoc']))
			{
				$model->attributes=$_POST['FileDoc'];
				$model->uploadedfile=CUploadedFile::getInstance($model,'uploadedfile');
				if($model->save())
					$this->redirect(array('/project/view','id'=>$model->project_id));
			}

			$this->render('update',array(
				'model'=>$model,
			));
		} else {
			throw new CHttpException(403);
		}
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		$model = $this->loadModel($id);
		if(Yii::app()->request->isPostRequest)
		{
			// we only allow deletion via POST request
			if(!Yii::app()->user->isManagerForOrg($model->project->org_id)){
				throw new CHttpException(403, 'You are not authorized for this action.');
			}
				$project_id = $model->project->id;
				$model->delete();

			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
			if(!isset($_GET['ajax'])) {
				$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('project/index', 'id'=>$project_id));
			}
		} else {
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
		}
	}

	/**
	 * Render a modal showing all FileDocs for the project id passed in
	 */
	public function actionListFiles($project_id)
	{
		if($this->getCurUser()->hasProjectAccess($project_id)){
		$dataProvider=new CActiveDataProvider('FileDoc',array('criteria'=>array('condition'=>'project_id='.$project_id,),));
		$this->renderModal('//FileDoc/_files',array('dataProvider'=>$dataProvider,));
		} else {
			throw new CHttpException(403, "You do not have access to this.", 403);
		}
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return FileDoc the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=FileDoc::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param FileDoc $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='file-doc-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
