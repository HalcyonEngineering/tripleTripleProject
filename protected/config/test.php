<?php

return CMap::mergeArray(
	require(dirname(__FILE__).'/main.php'),
	array(
		'components'=>array(
			'fixture'=>array(
				'class'=>'system.test.CDbFixtureManager',
			),
			'db'=>array(
				'connectionString'=>'sqlite:'.dirname(__FILE__).'/../data/test-pvms.sqlite',
				//'connectionString'=>'sqlite:'.dirname(__FILE__).'/../tests/fixtures/pvms-test.sqlite',
			),
			// uncomment the following to use a MySQL database
			/*
			'db'=>array(
				'connectionString' => 'mysql:host=localhost;dbname=blog-test',
				'emulatePrepare' => true,
				'username' => 'root',
				'password' => '',
				'charset' => 'utf8',
			),
			*/
		),
	)
);
