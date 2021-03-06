<?php

class AccountController extends Controller
{
	public $defaultAction = 'login';

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
			      'actions'=>array('login'),
			      'users'=>array('*'),
			),
			array('allow',
			      'actions'=>array('logout', 'settings', 'profile'),
			      'expression'=>'!Yii::app()->user->isGuest',
			),
			array('allow',
			      'actions'=>array('reset', 'passReset'),
			      'expression'=>'Yii::app()->user->isGuest',
			),
			array('allow',
			      'actions'=>array('adminLogin', 'orgDisable', 'orgEnable'),
			      'expression'=>'Yii::app()->user->isAdmin()',
			),
			
			array('deny',  // deny all users
			      'users'=>array('*'),
			),
		);
	}


	/**
	* Second login admin attempt
	* @TODO UI handle case for denied access and Not admin
	*/
	public function actionAdminLogin($userID) {
	// First check that the manager has allowed admin access
		if (Yii::app()->user->isAdmin()) { 
			if (User::model()->findByPk($userID)) {
				Yii::app()->user->setState('adminName', Yii::app()->user->name);
				$identity = new AccessIdentity($userID, new UserIdentity('admin', 'admin'));
				Yii::app()->user->login($identity);
				Yii::app()->user->setAdminAccess();
				if(Yii::app()->user->isAdminAccess()){
					$this->redirect(Yii::app()->user->returnUrl);
				}
			}
		}
		$this->redirect(array('organization/search'));
	}

	/**
	 * Displays the login page
	 */
	public function actionLogin()
	{
		if(Yii::app()->user->isGuest){
			if (!defined('CRYPT_BLOWFISH') || !CRYPT_BLOWFISH)
				throw new CHttpException(500, "This application requires that PHP was compiled with Blowfish support for crypt().");

			$model = new LoginForm;

			// if it is ajax validation request
			if (isset($_POST['ajax']) && $_POST['ajax'] === 'login-form') {
				echo CActiveForm::validate($model);
				Yii::app()->end();
			}

			// collect user input data
			if (isset($_POST['LoginForm'])) {
				$model->attributes = $_POST['LoginForm'];
				// validate user input and redirect to the previous page if valid
				if ($model->validate() && $model->login()){
					$this->redirect(Yii::app()->user->returnUrl);
				}
			}
			// display the login form
			$this->render('login', array('model' => $model));
		} elseif (Yii::app()->user->isVolunteer()){
			$this->redirect(array('role/index'));
		} elseif (Yii::app()->user->isManager()){
         /*   if ()*/
			$this->redirect(array('project/index'));
		} elseif (Yii::app()->user->isAdmin()){
			$this->redirect(array('organization/search'));
		} else {
			Yii::app()->user->logout();
			Yii::app()->user->setFlash('error', '<strong>User has been disabled.</strong><br>Please contact Pitch\'n <a href="http://www.pitchn.ca/contact/">here</a>.');
			$model = new LoginForm;
			$this->render('login', array('model' => $model));
		}
	}

	/**
	 * Logs out the current user and redirect to homepage.
	 */
	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
	}

	public function actionRegister() {
		$model = new User('register');

		// if it is ajax validation request
		if (isset($_POST['ajax']) && $_POST['ajax'] === 'user-register-form') {
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}

		//If registration information was supplied.
		if (isset($_POST['User'])) {
			$model->attributes = $_POST['User'];
			if ($model->validate()) {
				//Hash the password before saving it.
				$model->password = $model->hashPassword($model->newPassword);
				$identity = new UserIdentity($model->email, $model->newPassword);

				if ($model->save() && $identity->authenticate()) {
					Yii::app()->user->login($identity);
					Yii::app()->user->setFlash('success', 'Account successfully created.');
					$this->redirect(Yii::app()->user->returnUrl);
				}
			}

		}
		$this->render('register', array ('model' => $model));
	}

	public function actionReset()
	{
		$model = new User('register');
		//If email is supplied
		if (isset($_POST['User'])) {
			$model->attributes = $_POST['User'];
			$user = User::model()->findByAttributes(array('email' => $model->email));
			$hash = Yii::app()->securityManager->generateRandomString(32);
			if($user != null){
				Yii::app()->user->setFlash('success', 'Password reset email sent to ' . $model->email);
				
				$mail = new Mail;
				
				$mail->name = 'pitchn@pitchn.ca';
				$mail->subject = "Pitch'n - Password Reset";
				$mail->email = 'pitchn@pitchn.ca';
				$mail->Remail = $model->email;
				$mail->body = "Please follow the link below to reset your password: \r\n" . Yii::app()->getBaseUrl(true) . '/account/passreset?id=' . $user->id . '&hash=' . $hash. "\n\nThank you!\nPitch'n Team";
				
				$name='=?UTF-8?B?'.base64_encode($mail->name).'?=';
				$subject='=?UTF-8?B?'.base64_encode($mail->subject).'?=';
				$headers="From: $name <{$mail->email}>\r\n".
					"Reply-To: {$mail->email}\r\n".
					"MIME-Version: 1.0\r\n".
					"Content-Type: text/plain; charset=UTF-8";

				if(mail($mail->Remail,$subject,$mail->body,$headers)){
					Yii::app()->user->setFlash('contact','Your mail has been sent');
					$reset = new PasswordReset;
					$reset->timestamp = time();
					$reset->expiry = $reset->timestamp + 3600;
					$reset->user_id = $user->id;
					$reset->hash = $hash;
					$reset->save(false);
				}
			}
			else{
				Yii::app()->user->setFlash('error', 'No account exists with the provided email.');
			}
		}
		$this->render('reset', array ('model' => $model));
	}

	public function actionSettings()
	{
		if (Yii::app()->user->isGuest) {
			Yii::app()->user->loginRequired();
		}
		$model = User::model()->findByPk(Yii::app()->user->id);
		$model->setScenario('settings');

		// if it is ajax validation request
		if (isset($_POST['ajax']) && $_POST['ajax'] === 'user-settings-form') {
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}

		// If new settings were supplied.
		if (isset($_POST['User'])) {
			$_email = $model->email;
			$model->attributes = $_POST['User'];

			if ($model->validate()) {
				$_identity = new UserIdentity($_email, $model->origPassword);

				if (!$_identity->authenticate()) {
					Yii::app()->user->setFlash('error', 'Incorrect password.');
				} else {
					// Show new password.
					$_password = $model->newPassword;
					//Hash the password before saving it.
					if ($model->newPassword !== '') {
						$model->password = $model->hashPassword($model->newPassword);
					}
					
					if ($model->save()) {
						// Update email if it changed.
						$_identity->username=$model->email;
						Yii::app()->user->login($_identity);
						Yii::app()->user->setFlash('success', 'Account successfully changed.');
					}
				}

			}

		}

		$this->render('settings', array('model' => $model));
	}
	
	/**
	* Disables the account 
	*/
	public function actionOrgDisable($userID){
		$model = User::model()->findByPk($userID);
		$model->setScenario("disable");
		if($model->setAttribute('type', User::DISABLED) && $model->save()){
			Yii::app()->user->setFlash('success', 'Manager disabled.');
		}
		$this->redirect(array('organization/search'));
	}
	
		/**
	* Enables the account 
	*/
	public function actionOrgEnable($userID){
		$model = User::model()->findByPk($userID);
		$model->setScenario("enable");
		if($model->setAttribute('type', User::MANAGER) && $model->save()){
			Yii::app()->user->setFlash('success', 'Manager enabled.');
		}
		$this->redirect(array('organization/search'));
	}
	
	/**
	 * View profile.
	 */
	public function actionProfile(){

		$model = User::model()->findByPk(Yii::app()->user->id);

		// if it is ajax validation request
		if (isset($_POST['ajax']) && $_POST['ajax'] === 'login-form') {
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}

		if (isset($_POST['User'])){
			$model->attributes=$_POST['User'];
			if($model->validate() && $model->save()){
				Yii::trace(CVarDumper::dumpAsString($model));
				Yii::app()->user->setFlash('success', 'Profile updated.');
			} else {
				Yii::trace(CVarDumper::dumpAsString($model));
				Yii::app()->user->setFlash('error', 'Profile could not be updated.');
			}
		}

		$this->render('profile', array('model' => $model));
	}
	
	/**
	 *
	 */
	public function actionPassReset($id, $hash) {
		//find the account associated with the hash
		$model = User::model()->findbyPk($id);
		$reset = PasswordReset::model()->findbyPk($id);
		if (isset($_POST['User'])) {
			$_email = $model->email;
			$model->attributes = $_POST['User'];

			if ($model->validate()) {
					// Show new password.
					$_password = $model->newPassword;
					//Hash the password before saving it.
					if ($model->newPassword !== '') {
						$model->password = $model->hashPassword($model->newPassword);
					}
					
					if ($model->save()) {
						// Update email if it changed.
						$reset->delete();
						Yii::app()->user->setFlash('success', 'Password successfully reset!');
						$this->redirect(Yii::app()->user->returnUrl);
					}

			}
		}
		else {
			if($reset->hash == $hash) {
					if($reset->expiry > time()){
						$model->setScenario('passreset');
						$this->render('passreset', array('model' => $model));
					}
					else {
						Yii::app()->user->setFlash('error', 'Password expiry link has expired');
						$reset->delete();
						$this->redirect(Yii::app()->user->returnUrl);
					}
			}
			else {
				Yii::app()->user->setFlash('error', 'Invalid password reset link');
				$this->redirect(Yii::app()->user->returnUrl);
			}
		}	
	}
}
