<?php

class Mail extends CFormModel

{
	public $name;
	public $email;
	public $Remail;
	public $subject;
	public $bulkUserId;
	public $body;
	
	public function rules()
	{
		return array(
			// name, email, subject and body are required
			array('name, email, subject, body', 'required'),
			array('Remail', 'required', 'except'=>'bulk'),
		    array('bulkUserId, Remail', 'safe', 'on'=>'bulk'),
			// email has to be a valid email address
			array('email, Remail', 'email'),
		);
	}
	
    public function attributeLabels()
	{
		return array(
			'name' => 'Your Name',
			'email' => 'Your Email',
            'Remail' => 'Recipient\'s Email',
			'subject' => 'Email Subject',
			'body' => 'Message',
		);
	}

	/**
	 * Sends an email.
	 * @return bool
	 */
	public function sendMail() {
		Yii::log('Message sendmail on email with name, email, Remail, subject, body: '.$this->name.', '.$this->email.', '.$this->Remail.', '.$this->subject.', '.$this->body, 'warning','Mail'); //TODO: remove debug

		$sent_name='=?UTF-8?B?'.base64_encode($this->name).'?=';
		$sent_subject='=?UTF-8?B?'.base64_encode($this->subject).'?=';
		$sent_headers="From: $sent_name <{$this->email}>\r\n".
			"Reply-To: {$this->email}\r\n".
			"MIME-Version: 1.0\r\n".
			"Content-Type: text/plain; charset=UTF-8";
		Yii::beginProfile('phpMail');
		$success = mail($this->Remail,$sent_subject,$this->body,$sent_headers);
		Yii::endProfile('phpMail');
		if (!$success)
			Yii::log('Warning sendmail failed','warning','Mail');
		return $success;
	}
}