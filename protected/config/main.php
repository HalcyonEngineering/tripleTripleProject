<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'Volunteer Management System',
    'timeZone'=>'America/Vancouver',

	// preloading 'log' component
	'preload'=>array(
		'log',
		'bootstrap'),

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
                'application.extensions.EAdvancedArBehavior',
	),

	'defaultController'=>'account',
	
	'modules'=>array(
		// uncomment the following to enable the Gii tool
		
		'gii'=>array(
			'class'=>'system.gii.GiiModule',
			'password'=>'pvmsgii',
			// If removed, Gii defaults to localhost only. Edit carefully to taste.
			'ipFilters'=>array('127.0.0.1','::1'),
			'generatorPaths'=>array(
				'bootstrap.gii',
			),
		),
		
	),
	// application components
	'components'=>array(
		'authManager'=>array(
			'class'=>'CPhpAuthManager',
		),

		'bootstrap'=> array(
			'class' => 'ext.yii-booster.components.Bootstrap',
		),
		'db'=>require(dirname(__FILE__).'/db.php'),

		'errorHandler'=>array(
			// use 'site/error' action to display errors
			'errorAction'=>'site/error',
		),
		'fixture'=>array(
            'class'=>'system.test.CDbFixtureManager',
        ),
		'urlManager'=>array(
			'urlFormat'=>'path',
			'rules'=>array(
				'post/<id:\d+>/<title:.*?>'=>'post/view',
				'posts/<tag:.*?>'=>'post/index',
				'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
			),
		    'showScriptName'=>false,
		),
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning',
				),
				array(
					'class'=>'CProfileLogRoute',
					'report'=>'summary',
					'enabled'=>YII_DEBUG,
				),
				// uncomment the following to show log messages on web pages

				array(
					'class'=>'CWebLogRoute',
					'enabled'=>YII_DEBUG,
				),
				
			),
		),
	    'request'=>array(
		    'enableCsrfValidation'=>true,
		    'enableCookieValidation'=>true,
	    ),
	    'user'=>array(
		    // enable cookie-based authentication
		    'allowAutoLogin'=>true,
		    'loginUrl' => array('/account/login'),
		    'class' => 'WebUser',
	    ),
	    'widgetFactory'=>require(dirname(__FILE__).'/widgets.php')
	),

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>require(dirname(__FILE__).'/params.php'),
);
